<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Product;
use App\Models\Share;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function share(Product $product)
    {
        if (Product::find($product->id)->status === 'drafted') {
            return response()->json(['error' => true, 'message' => 'You can not share a product that is in drafted mode'], 403);
        }
        $shared = Share::create(['user_id' => Auth::id(), 'product_id' => $product->id]);
        $productOwner = $product->user;
        $followers = Follower::whereUserId(Auth::id())->get();
        if ($followers->isNotEmpty()) {
            foreach ($followers as $follower) {
                if ($productOwner['id'] !== $follower['follower_id']) {
                    NotificationController::SharedProductFollowerNotification(User::find($follower['follower_id']), $shared);
                }
            }
        }
        if ($productOwner && Auth::id() != $productOwner->id) {
            NotificationController::newSharedProductNotification($productOwner, $shared);
        }
        return response()->json(['error' => false, 'message' => 'You have successfully shared this product'], 200);
    }
}
