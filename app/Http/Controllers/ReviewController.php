<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function storeReview(Product $product, Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer'
        ]);
        if (Review::reviewExists(Auth::user(), $product) === false) {
            if (empty($request['rating'])) {
                $request['rating'] = 0;
            }
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $product['id'],
                'content' => $request['content'],
                'rating' => $request['rating']
            ]);
            return response()->json(['error' => false, 'message' => 'You have successfully reviewed on this product'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You have already reviewed on this product'], 403);
    }

    public function updateReview(Product $product, Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'rating' => 'integer'
        ]);
        if (Review::reviewExists(Auth::user(), $product) == true) {
            if (empty($request['rating'])) {
                $request['rating'] = 0;
            }
            $review = Review::find($request['review_id']);
            $review->content = $request['content'];
            $review->rating = $request['rating'];
            $review->save();
            return response()->json(['error' => false, 'message' => 'You have successfully updated review on this product'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 403);
    }

    public function editReview(Product $product)
    {
        Review::whereUserId(Auth::id())->whereProductId($product->id)->first();
    }
}
