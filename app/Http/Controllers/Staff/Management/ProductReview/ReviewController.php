<?php

namespace App\Http\Controllers\Staff\Management\ProductReview;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Remote\Remote;
use App\Models\Enterprise\ProductReview\Product;
use App\Models\Enterprise\ProductReview\Group;
use App\Models\Enterprise\ProductReview\Review;
use App\Models\Enterprise\ProductReview\FacebookId;
use App\Models\Social\Product as SicialProduct;
use App\Models\Social\ProductAttr;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use App\Jobs\ProductReview\ApproveReviewJob;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-review')) {
            abort(403);
        }
        $reviews = Review::with('reviewer')
            ->with('product')
            ->orderBy('created_at');

        if ($createdAtFrom = $request->input('created_at_from')) {
            $reviews = $reviews->where('created_at', '>=', Carbon::createFromFormat('Y-m-d', $createdAtFrom)->startOfDay());
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
        
        return view('staff.management.product_review.review.index', compact('reviews', 'count0', 'count1', 'count2', 'count3', 'count4'));
    }

    public function approve($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $review = Review::findOrFail($id);
        $amount = 500;

        if ($request->input('amount')) {
            $amount = (int) $request->input('amount');
        }

        $this->dispatch(new ApproveReviewJob($review, $request->input('note'), $amount, auth()->guard('staff')->user()->id));

        return redirect()->route('Staff::Management::productReview@review@index')
            ->with('success', 'Bài đánh giá đã được thêm vào queue');
    }

    public function disapprove($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $review = Review::findOrFail($id);
        $review->update([
            'note' => $request->input('note'),
            'status' => Review::STATUS_DISAPPROVED,
        ]);
        $review->product()->decrement('review_count');

        return redirect()->route('Staff::Management::productReview@review@index')
            ->with('success', 'Bài đánh giá đã bị huỷ');
    }

    public function batchApprove(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));
        $reviews = Review::whereIn('id', $ids)->get();
        $amount = 500;

        if ($request->input('amount')) {
            $amount = (int) $request->input('amount');
        }

        foreach ($reviews as $review) {
            $this->dispatch(new ApproveReviewJob($review, $request->input('note'), $amount, auth()->guard('staff')->user()->id));
        }

        return redirect()->back()
            ->with('success', 'Bài đánh giá đã được thêm vào queue');
    }

    public function batchDisapprove(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));
        $reviews = Review::whereIn('id', $ids)->get();

        foreach ($reviews as $review) {
            $review->update([
                'note' => $request->input('note'),
                'status' => Review::STATUS_DISAPPROVED,
            ]);
            $review->product()->decrement('review_count');
        }

        return redirect()->back()
            ->with('success', 'Bài đánh giá đã bị huỷ');
    }
}
