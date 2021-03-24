<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Like;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Product $product)
    {
        if ($product->status == 'drafted') {
            return response()->json(['error' => True, 'message' => 'You can not like a product that is in drafted mode'], 403);
        }
        if (Product::isProductLiked(Auth::user(), $product) == true) {
            return response()->json(['error' => True, 'message' => 'You have already liked this product'], 401);
        }
        $liked = Like::create(
            ['user_id' => Auth::id(),
                'product_id' => $product->id]
        );
        $productOwner = $product->user;
        $followers = Follower::whereUserId(Auth::id())->get();
        if ($followers->isNotEmpty()) {
            foreach ($followers as $follower) {
                if ($productOwner['id'] !== $follower['follower_id']) {
                    NotificationController::LikedProductFollowerNotification(User::find($follower['follower_id']), $liked);
                }
            }
        }
        if ($productOwner && Auth::id() != $productOwner->id) {
            NotificationController::newLikedProductNotification($productOwner, $liked);
        }
        return response()->json(['error' => False, 'message' => 'Product has been like'], 200);
    }

    public function unLike(Product $product)
    {
        if (Product::isProductLiked(Auth::user(), $product) == true) {
            Like::whereUserId(Auth::id())->whereProductId($product->id)->first()->delete();
            return response()->json(['error' => False, 'message' => 'Product has been Unliked'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 401);
    }
}
