<?php

namespace App\Http\Controllers;

use App\Models\FollowBrand;
use App\Models\Brand;
use App\Models\File;
use App\Models\FollowCategory;
use App\Models\Follower;
use App\Models\Product;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Feed
{
    public function getProducts(Request $request)
    {
        $user = Auth::user();
        $page = 1;
        $limitProducts = 10;
        if (isset($request['page'])) {
            $page = (int)$request['page'];
        }
        $currentOffset = ($page * $limitProducts) - $limitProducts;

        $categoryProducts = FollowCategory::getCategoryProducts($user, $currentOffset, $limitProducts);
        $brandProducts = FollowBrand::getBrandProducts($user, $currentOffset, $limitProducts);
        $followingsProducts = Follower::getFollowingsProducts($user, $currentOffset, $limitProducts);
        $followingsSharedProducts = Share::getFollowingsSharedProducts($user, $currentOffset, $limitProducts);
        $sharedProducts = Share::getSharedProducts($user, $currentOffset, $limitProducts);

        $products = $categoryProducts->merge($brandProducts)->merge($followingsProducts)->unique('id');
        $products = $followingsSharedProducts->concat($products);
        $allProducts = $sharedProducts->concat($products)->sortByDesc('created_at')->values()->all();
        foreach ($allProducts as $product) {
            $productFiles = $product['files'];
            $productBrand = $product['brand'];
            $productUser = User::whereId($product['user_id'])->get()->first();
            $productUserFiles = $productUser['files'];

            // Share user details are in same object as product details
            $product['productShareUser'] = [];
            if (isset($product['username'])) {
                $productShareUser['username'] = $product['username'];
                $productShareUser['profile_url'] = route('profile.index', ['user' => $productShareUser['username']]);
                $productShareUser['first_name'] = $product['first_name'];
                $productShareUser['last_name'] = $product['last_name'];
                $productShareUser['profile_picture'] = '';
                $shareUserProfileImage = File::whereId($product['profile_picture'])->first();
                if ($shareUserProfileImage) {
                    $productShareUser['profile_picture'] = asset('storage/' . $shareUserProfileImage->path);
                }
                $product['productShareUser'] = $productShareUser;
            }

            $product_user_details = [];
            $product_user_details['id'] = $productUser->id;
            $product_user_details['follow_url'] = '';
            if (!isUserFollowing($productUser)) {
                $product_user_details['follow_url'] = route('follow.user', ['user' => $productUser->username]);
            }
            $product_user_details['name'] = $productUser->first_name . ' ' . $productUser->last_name;
            $product_user_details['username'] = $productUser->username;
            $product_user_details['profile_url'] = route('profile.index', ['user' => $productUser->username]);
            if ($productUserFiles && count($productUserFiles) > 0) {
                $product_user_details['profile_image'] = asset('storage/' . $productUserFiles[0]->path);
            } else {
                $product_user_details['profile_image'] = null;
            }


            // Otherwise it doesn't show --__--
            foreach ($product['categories'] as $productCategory) {
                $productCategory['url'] = route('category.show', ['category' => $productCategory['slug']]);
            }

            $product['product_user'] = $product_user_details;
            $product['price'] = '$' . number_format($product['price'], 2);
            $product['brand_name'] = $productBrand['name'];
            $product['url'] = route('products.show', ['user' => $productUser['username'], 'product' => $product['slug']]);
            $product['has_liked'] = $product->hasUserLiked(Auth::user());
            if ($product['has_liked']) {
                $product['like_url'] = route('product.unlike', ['product' => $product['id']]);
            } else {
                $product['like_url'] = route('product.like', ['product' => $product['id']]);
            }
            $product['share_url'] = route('product.share', ['product' => $product['id']]);

            if ($productFiles && count($productFiles) > 0) {
                $product['image'] = asset('storage/' . $productFiles[0]['path']);
            } else {
                $product['image'] = null;
            }
        }
        return response()->json($allProducts, 200);
    }

    public function getBrands()
    {
        $followingsBrandIds = [];
        if(Auth::user()) {
            $userBrands = Auth::user()->followedBrands;
            foreach ($userBrands as $userBrand) {
                $followingsBrandIds[] = $userBrand['brand_id'];
            }
        }
        $brands = Brand::whereNotIn('id', $followingsBrandIds)->limit(6)->get();
        if ($brands->isEmpty()) {
            return response()->json(['error' => true, 'message' => 'No brand found'], 404);
        }
        $data = [];
        foreach ($brands as $brand) {
            $brand['follow_url'] = route('brand.follow', $brand);
            $brand['url'] = route('brand.show', $brand);
            $brand['image'] = $brand->file ? asset('storage/' . $brand->file['path']) : null;
            $data[] = array_merge(['product_count' => Product::whereBrandId($brand['id'])->get()->count()], $brand->toArray());
        }
        $sort = array_column($data, 'product_count');
        array_multisort($sort, SORT_DESC, $data);
        $brands = array_slice($data, 0, 10);
        return response()->json($brands, 200);
    }

    public function getFollowers()
    {

        $user = Auth::user();
        $followers = $user->followers;
        if ($followers->isEmpty()) {
            return response()->json(['error' => true, 'message' => 'No follower found'], 404);
        }
        $data = [];
        foreach ($followers as $follower) {
            $user = User::find($follower['follower_id']);
            $user = $this->addUserURLs($user);
            $popularityAverage = User::popularityMeter($user);
            $data[] = ['popularityAverage' => $popularityAverage, 'follower' => $user];
        }
        $sort = array_column($data, 'popularityAverage');
        array_multisort($sort, SORT_DESC, $data);
        $followers = array_slice($data, 0, 10);
        return response()->json($followers, 200);
    }

    public function getPeopleToKnow()
    {
        $user = Auth::user();
        $followings = $user->following;
        if ($followings->isEmpty()) {
            $usersToFollow = User::all()->except(Auth::id())->take(6);
            foreach ($usersToFollow as $user) {
                $user = $this->addUserURLs($user);
            }
            return response()->json($usersToFollow, 200);
        }
        $followingsIds = [];
        foreach ($followings as $following) {
            $followingsIds[] = $following['user_id'];
        }
        $followingsIds[] = Auth::id();
        $usersToFollow = User::whereNotIn('id', $followingsIds)->limit(6)->get();

        foreach ($usersToFollow as $user) {
            $user = $this->addUserURLs($user);
        }

        return response()->json($usersToFollow, 200);
    }

    public function addUserURLs($user)
    {
        $user['profile_url'] = route('profile.index', ['user' => $user->username]);
        $user['follow_url'] = route('follow.user', ['user' => $user->username]);
        if ($user->profileImage) {
            $user['profile_image_url'] = asset('storage/' . $user->profileImage['path']);
        } else {
            $user['profile_image_url'] = null;
        }
        return $user;
    }
}
