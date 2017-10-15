<?php

namespace App\Http\Controllers\Ajax\Analytics\Realtime;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Product;
//use App\Models\Social\MProduct;
use App\Models\Icheck\Product\Product as MProduct;
use Auth;

class TopProductController extends Controller
{
    public function topScan()
    {
        $gln = Auth::user()->gln->lists('id')->toArray();
        $products = Product::select(['barcode', 'name'])->whereIn('gln_id', $gln)->orderBy('created_at', 'desc')->get();
        $mProducts = MProduct::whereIn('gtin_code', $products->lists('barcode')->toArray())->orderBy('scan_count', 'desc')->take(10)->get();

        $data = [];

        $products = $products->keyBy('barcode');

        foreach ($mProducts as $product) {
            $data[] = [
                'gtinCode' => (string) $product->gtin_code,
                'productName' => $product->product_name,
                'scanCount' => $product->scan_count,
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function topLike()
    {
        $gln = Auth::user()->gln->lists('id')->toArray();
        $products = Product::select(['barcode', 'name'])->whereIn('gln_id', $gln)->orderBy('created_at', 'desc')->get();
        $mProducts = MProduct::whereIn('gtin_code', $products->lists('barcode')->toArray())->orderBy('like_count', 'desc')->take(10)->get();

        $data = [];

        $products = $products->keyBy('barcode');

        foreach ($mProducts as $product) {
            $data[] = [
                'gtinCode' => (string) $product->gtin_code,
                'productName' =>  $product->product_name,
                'likeCount' => $product->like_count,
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function topComment()
    {
        $gln = Auth::user()->gln->lists('id')->toArray();
        $products = Product::select(['barcode', 'name'])->whereIn('gln_id', $gln)->orderBy('created_at', 'desc')->get();
        $mProducts = MProduct::whereIn('gtin_code', $products->lists('barcode')->toArray())->orderBy('comment_count', 'desc')->take(10)->get();

        $data = [];

        $products = $products->keyBy('barcode');

        foreach ($mProducts as $product) {
            $data[] = [
                'gtinCode' => (string) $product->gtin_code,
                'productName' =>  $product->product_name,
                'commentCount' => $product->comment_count,
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function topVote()
    {
        $gln = Auth::user()->gln->lists('id')->toArray();
        $products = Product::select(['barcode', 'name'])->whereIn('gln_id', $gln)->orderBy('created_at', 'desc')->get();
        $mProducts = MProduct::whereIn('gtin_code', $products->lists('barcode')->toArray())->orderBy('vote_good_count', 'desc')->take(10)->get();

        $data = [];

        $products = $products->keyBy('barcode');

        foreach ($mProducts as $product) {
            $data[] = [
                'gtinCode' => (string) $product->gtin_code,
                'productName' => $product->product_name,
                'voteCount' => $product->vote_good_count + $product->vote_normal_count + $product->vote_bad_count,
                'voteAverage' => $product->vote_good_count,
            ];
        }

        return response()->json(['data' => $data]);
    }
}
