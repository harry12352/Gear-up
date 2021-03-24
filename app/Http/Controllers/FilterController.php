<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function getProducts(Request $request)
    {
        //validate incoming request
        $request->validate(
            [   'brand[]' => 'integer|nullable',
                'category' => 'integer|nullable',
                'size[]' => 'integer|nullable',
                'color[]' => 'integer|nullable',
                'price_min' => 'integer|nullable',
                'price_max' => 'integer|nullable',
                'sortBy' => 'string|nullable',
                'username' => 'string|nullable'
            ]
        );

        //get products when color and category both are given
        if ($request['color'] !== null && $request['category'] !== null) {
            $colors = explode(',', $request['color']);
            if (count($colors) == 1) {
                $base = Product::join('category_product', 'category_product.product_id', '=', 'products.id')
                    ->join('color_product', 'color_product.product_id', '=', 'category_product.product_id')
                    ->where('color_product.color_id', $colors[0])
                    ->where('products.status', 'published')
                    ->select('products.*');
            } else {
                $base = Product::join('category_product', 'category_product.product_id', '=', 'products.id')
                    ->join('color_product', 'color_product.product_id', '=', 'category_product.product_id')
                    ->whereIn('color_product.color_id', $colors)
                    ->where('products.status', 'published')
                    ->distinct('id')
                    ->select('products.*');
            }
        }
        //get products when category is given
        if (!($request['color'] !== null && $request['category'] !== null) && $request['category'] !== null) {
            if (Category::find($request['category']) != null) {
                $base = Category::whereId($request['category'])->first()->products()->where('status', '=', 'published');
            } else {
                return response()->json(['data' => []]);
            }
        }

        //when Color is given
        if (!($request['color'] !== null && $request['category'] !== null) && $request['color'] !== null) {
            $colors = explode(',', $request['color']);
            if (count($colors) == 1) {
                if (Color::whereId($colors[0])->exists()) {
                    if (Color::find($colors[0])->products()->get()->isEmpty()) {
                        return response()->json(['data' => []]);
                    }
                    $base = Color::find($colors[0])->products()->where('status', 'published');
                } else {
                    return response()->json(['data' => []]);
                }
            } else {
                if (Color::whereIn('id', $colors)->get()->isNotEmpty()) {
                    $base = Product::join('color_product', 'color_product.product_id', '=', 'products.id')
                        ->whereIn('color_product.color_id', $colors)->select('products.*')->where('status', 'published')->distinct('products.id');
                    if ($base->get()->isEmpty()) {
                        return response()->json(['data' => []]);
                    }
                } else {
                    return response()->json(['data' => []]);
                }
            }
        }
        //get user related products
        if ($request['username'] !== null) {
            $user = User::where('username', $request['username'])->first();
            if ($user !== null) {
                if (empty($base)) {
                    $base = Product::where('user_id', $user['id'])->where('status', 'published');
                } else {
                    $base->whereUserId($user['id']);
                }
            } else {
                return response()->json(['data' => []]);
            }
        }

        //get products when brand is given
        if ($request['brand'] !== null) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            $brands = explode(',', $request['brand']);
            if (count($brands) == 1) {
                $base->where(
                    function ($query) use ($brands) {
                        $query->where('brand_id', $brands[0]);
                    }
                );
            } else {
                $base->where(
                    function ($query) use ($brands) {
                        foreach ($brands as $brand) {
                            $query->orWhere('brand_id', '=', $brand);
                        }
                    }
                );
            }
        }
        //get products when price range and size and color is given
        if (($request['price_min'] !== null && $request['price_max'] !== null) && ($request['size'] !== null && $request['color'] !== null)) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            $sizes = explode(',', $request['size']);
            $base = self::getSizesRelatedBase($base, $sizes);

            $allProducts = $base->whereBetween('price', [$request['price_min'], $request['price_max']])->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }

        //get products when price_min or price_max and size and color is given
        if (($request['price_min'] !== null || $request['price_max'] !== null) && ($request['size'] !== null && $request['color'] !== null)) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            $sizes = explode(',', $request['size']);
            $base = self::getSizesRelatedBase($base, $sizes);
            if ($request['price_min'] !== null) {
                $allProducts = $base->where('price', '>=', $request['price_min'])->paginate(12);
                return self::attachProductDetails($request, $allProducts);
            }
            $allProducts = $base->where('price', '<=', $request['price_max'])->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }

        //get products when price range and size or color is given
        if (($request['price_min'] !== null && $request['price_max'] !== null) && ($request['size'] !== null || $request['color'] !== null)) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            if ($request['size']) {
                $sizes = explode(',', $request['size']);
                $base = self::getSizesRelatedBase($base, $sizes);
            }
            $allProducts = $base->whereBetween('price', [$request['price_min'], $request['price_max']])->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }

        //get products when price_min or price_max and size or color is given
        if (($request['price_min'] !== null || $request['price_max'] !== null) && ($request['size'] !== null || $request['color'] !== null)) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            if ($request['size']) {
                $sizes = explode(',', $request['size']);
                $base = self::getSizesRelatedBase($base, $sizes);
            }
            if ($request['price_min'] !== null) {
                $allProducts = $base->where('price', '>=', $request['price_min'])->paginate(12);
                return self::attachProductDetails($request, $allProducts);

            }
            $allProducts = $base->where('price', '<=', $request['price_max'])->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }
        //get products when size
        if ($request['size'] !== null) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            $sizes = explode(',', $request['size']);
            $base = self::getSizesRelatedBase($base, $sizes);
            $allProducts = $base->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }
        //get products when price_min and price-max range is given
        if ($request['price_min'] !== null && $request['price_max'] !== null) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            $allProducts = $base->whereBetween('price', [$request['price_min'], $request['price_max']])->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }

        //get products when price_min or price-max range is given
        if ($request['price_min'] !== null || $request['price_max'] !== null) {
            if (empty($base)) {
                $base = Product::where('status', '=', 'published');
            }
            if ($request['price_min'] !== null) {
                $allProducts = $base->where('price', '>=', $request['price_min'])->paginate(12);
                return self::attachProductDetails($request, $allProducts);
            }
            $allProducts = $base->where('price', '<=', $request['price_max'])->paginate(12);
            return self::attachProductDetails($request, $allProducts);
        }

        //get default products
        if (empty($base)) {
            $base = Product::where('status', '=', 'published');
        }
        $allProducts = $base->paginate(12);
        return $this->attachProductDetails($request, $allProducts);
    }

    public function attachProductDetails(Request $request, $allProducts)
    {
        foreach ($allProducts as $product) {

            $productFiles = $product['files'];
            $productBrand = $product['brand'];
            $productUser = User::whereId($product['user_id'])->get()->first();
            $productUserFiles = $productUser['files'];
            $productShares = $product['shares'];
            $productColors = $product['colors'];
            $product_user_details = [];
            $product_user_details['id'] = $productUser['id'];
            $product_user_details['name'] = $productUser['firstname'] . ' ' . $productUser['lastname'];
            $product_user_details['username'] = $productUser['username'];
            $product_user_details['profile_url'] = route('profile.index', ['user' => $productUser['username']]);
            if ($productUserFiles && count($productUserFiles) > 0) {
                $product_user_details['profile_image'] = asset('storage/' . $productUserFiles[0]['path']);
            } else {
                $product_user_details['profile_image'] = null;
            }
            // Otherwise it doesn't show --__--
            foreach ($product['categories'] as $productCategory) {
                $productCategory['url'] = route('category.show', ['category' => $productCategory['slug']]);
            }
            $product['product_user'] = $product_user_details;
            $product['price'] = $product['price'];
            $product['brand_name'] = $productBrand['name'];
            $product['url'] = route('products.show', ['user' => $productUser['username'], 'product' => $product['slug']]);
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
        if ($request['sortBy'] === 'title_asc') {

            return response()->json(['data' => $allProducts->sortBy('title')->values()->all()], 200);
        }
        if ($request['sortBy'] === 'title_desc') {
            return response()->json(['data' => $allProducts->sortByDesc('title')->values()->all()], 200);
        }
        if ($request['sortBy'] === 'price_asc') {

            return response()->json(['data' => $allProducts->sortBy('price')->values()->all()], 200);
        }
        if ($request['sortBy'] === 'price_desc') {

            return response()->json(['data' => $allProducts->sortByDesc('price')->values()->all()], 200);
        }
        if ($request['sortBy'] === 'date_asc') {

            return response()->json(['data' => $allProducts->sortBy('created_at')->values()->all()], 200);
        }

        if ($request['sortBy'] === 'date_desc') {

            return response()->json(['data' => $allProducts->sortByDesc('created_at')->values()->all()], 200);
        }
        return response()->json($allProducts, 200);
    }

    public function getSizesRelatedBase($base, $sizes)
    {
        if (count($sizes) == 1) {
            $base->where(
                function ($query) use ($sizes) {
                    $query->where('size_id', $sizes[0]);
                }
            );
        } else {
            $base->where(
                function ($query) use ($sizes) {
                    foreach ($sizes as $size) {
                        $query->orWhere('size_id', $size);
                    }
                }
            );
        }
        return $base;
    }

    public function getAllproducts()
    {
        $base = Product::where('status', '=', 'published');
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');
        return view('global.all-products',
            [
                'brands' => Brand::all(), 'categories' => Category::all(),
                'colors' => Color::all(), 'products' => $base->paginate(12),
                'sizes' => Size::all(), 'min_price' => $minPrice,
                'max_price' => $maxPrice
            ]
        );
    }

}
