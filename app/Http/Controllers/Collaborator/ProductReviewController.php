<?php

namespace App\Http\Controllers\Collaborator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Remote\Remote;
use App\Models\Enterprise\ProductReview\Product;
use App\Models\Enterprise\ProductReview\Review;
use App\Models\Social\ProductAttr;
use Carbon\Carbon;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::whereHas('reviewer', function ($query) {
            $query->where('id', auth()->guard('collaborator')->user()->id);
        })->orderBy('created_at', 'desc');

        if ($createdAtFrom = $request->input('created_at_from')) {
            $reviews = $reviews->where('created_at', '>=', Carbon::createFromFormat('Y-m-d', $createdAtFrom));
        }

        if ($createdAtTo = $request->input('created_at_to')) {
            $reviews = $reviews->where('created_at', '<=', Carbon::createFromFormat('Y-m-d', $createdAtTo)->endOfDay());
        }

        $count0 = clone $reviews;
        $count0 = $count0->where('status', Review::STATUS_PENDING_APPROVAL)->count();

        $count1 = clone $reviews;
        $count1 = $count1->where('status', Review::STATUS_DISAPPROVED)->count();

        $count2 = clone $reviews;
        $count2 = $count2->where('status', Review::STATUS_APPROVED)->count();

        $count3 = clone $reviews;
        $count3 = $count3->where('status', Review::STATUS_IN_PROGRESS)->count();

        $count4 = clone $reviews;
        $count4 = $count4->where('status', Review::STATUS_ERROR)->count();

        if ($request->has('status') and ($status = $request->input('status')) !== '') {
            $reviews = $reviews
                ->where('status', $status)->paginate(10);
        } else {
            $reviews = $reviews
                ->whereIn('status', [Review::STATUS_PENDING_APPROVAL, Review::STATUS_ERROR, Review::STATUS_IN_PROGRESS])->paginate(10);
        }

        return view('collaborator.product_review.index', compact('reviews', 'count0', 'count1', 'count2', 'count3', 'count4'));
    }

    public function add(Remote $remote, Request $request)
    {
        $product = Product::whereHas('reviewingBy', function ($query) {
            $query->where('id', auth()->guard('collaborator')->user()->id);
        })->first();

        if (is_null($product)) {
            $ignoreIds = $request->session()->get('collaborator.product_review.ignore_ids') ?: [];
            $ok = false;

            while (!$ok) {
                $product = Product::whereRaw('(`max_review` <= 0 OR `review_count` < `max_review`)')
                    ->whereNotIn('id', $ignoreIds)
                    ->where(function ($query) {
                        $query->whereNull('reviewing_by')
                            ->orWhere('updated_at', '<', Carbon::now()->subHours(6));
                    })
                    ->orderBy('review_count', 'asc')
                    ->first();

                if (is_null($product)) {
                    return 'no product';
                }

                $remoteProduct = $remote->service('product')->get($product->gtin);

                if (isset($remoteProduct->code) and $remoteProduct->code == 404) {
                    $ignoreIds[] = $product->id;

                    if (count($ignoreIds) > 10) {
                        return 'no product';
                    }

                    continue;
                }

                $ok = true;
                $product->update(['cached_info' => $remoteProduct]);
                $product->reviewingBy()->associate(auth()->guard('collaborator')->user()->id);
                $product->save();
            }
        }

        $attrs = ProductAttr::all()->keyBy('id');

        return view('collaborator.product_review.form', compact('product', 'attrs'));
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $product = $review->product;
        $attrs = ProductAttr::all()->keyBy('id');

        return view('collaborator.product_review.form', compact('review', 'product', 'attrs'));
    }

    public function next(Request $request)
    {
        $oldProduct = Product::whereHas('reviewingBy', function ($query) {
            $query->where('id', auth()->guard('collaborator')->user()->id);
        })->first();

        if (!is_null($oldProduct)) {
            $oldProduct->reviewingBy()->dissociate();
            $oldProduct->save();
            $request->session()->push('collaborator.product_review.ignore_ids', $oldProduct->id);
        }

        return redirect()->route('Collaborator::productReview@add');
    }

    public function submitReview(Request $request)
    {
        $gtin = $request->input('gtin');

        $product = Product::where(['gtin' => $gtin])->firstOrFail();

        // $product > max

        $product->increment('review_count');
        $product->reviewingBy()->dissociate();
        $product->save();

        $review = Review::create($request->all());
        $review->reviewer()->associate(auth()->guard('collaborator')->user()->id);
        $review->product()->associate($request->input('gtin'));
        $review->status = Review::STATUS_PENDING_APPROVAL;
        $review->save();

        return redirect()->route('Collaborator::productReview@add')
            ->with('success', 'Đã gửi bài đánh giá')
            ->with('new', [$review->id]);
    }

    public function update($id, Request $request)
    {
        $review = Review::findOrFail($id);
        $review->update($request->all());

        return redirect()->route('Collaborator::productReview@edit', $review->id)
            ->with('success', 'Đã cập nhật nội dung đánh giá');
    }

    public function delete($id, Request $request)
    {
        $review = Review::findOrFail($id);

        if ($review->status == Review::STATUS_PENDING_APPROVAL) {
            $review->product()->decrement('review_count');
        }

        $review->delete();

        return redirect()->route('Collaborator::productReview@index')
            ->with('success', 'Đã xoá đánh giá');
    }
}
