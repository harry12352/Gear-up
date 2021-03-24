<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FollowCategory;
use Illuminate\Support\Facades\Auth;

class FollowCategoryController extends Controller
{
    public function follow(Category $category)
    {
        if (Category::isCategoryFollowed($category) === true) {
            return response()->json(['error' => true, 'message' => 'You have already followed this Category'], 401);
        }
        FollowCategory::create(
            ['user_id' => Auth::id(),
                'category_id' => $category->id]
        );
        return response()->json(['error' => false, 'message' => 'Category has been followed'], 200);
    }

    public function unFollow(Category $category)
    {
        if (Category::isCategoryFollowed($category) === true) {
            FollowCategory::whereUserId(Auth::id())->whereCategoryId($category->id)->first()->delete();
            return response()->json(['error' => false, 'message' => 'Category has been unfollowed'], 200);
        }
        return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 401);
    }

    public function showProducts(Category $category)
    {
        return view('category.view', ['category' => $category, 'products' => $category->products()->where('status', 'published')->paginate(12)]);
    }
}
