<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\FollowBrand;
use Illuminate\Support\Facades\Auth;

class FollowBrandController extends Controller
{
    public function follow(Brand $brand)
    {
        if (Brand::isBrandFollowed($brand) == false) {
            FollowBrand::create(
                [
                    'user_id' => Auth::id(),
                    'brand_id' => $brand['id']
                ]
            );
            return response()->json(['error' => false, 'message' => 'Brand has been followed successfully'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You You have already followed this Brand'], 401);
    }

    public function unFollow(Brand $brand)
    {
        if (Brand::isBrandFollowed($brand) == true) {
            FollowBrand::whereUserId(Auth::id())->whereBrandId($brand['id'])->delete();
            return response()->json(['error' => false, 'message' => 'Brand has been unfollowed successfully'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 401);
    }

    public function showProducts(Brand $brand)
    {
        return view('brand.view', ['products' => $brand->products()->where('status', 'published')->paginate(12), 'brand' => $brand]);
    }
}
