<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Brand;
use App\Models\Category;
use App\Models\File;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Review;
use App\Models\Share;
use App\Models\User;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user)
    {
        if (self::isProductOwner($user) === true) {
            $products = $user->products()->paginate(12);
        } else {
            $products = $user->products()->where('status', '=', 'published')->paginate(12);
        }
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');
        return view('profile.products')->with(
            [
                'user' => $user, 'products' => $products, 'brands' => Brand::all(),
                'categories' => Category::all(), 'colors' => Color::all(),
                'sizes' => Size::all(),
                'min_price' => $minPrice, 'max_price' => $maxPrice
            ]
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(User $user)
    {
        if (ProductController::isProductOwner($user) === false) {
            return redirect()->back()->with('error', 'You\'re not authorized to perform this action!');
        }
        return view('profile.products.create', ['brands' => Brand::all(), 'categories' => Category::all(), 'colors' => Color::all(), 'user' => Auth::user(), 'sizes' => Size::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request, User $user)
    {
        if (self::isProductOwner($user) == false) {
            return redirect()->back()->with('error', 'You\'re not authorized to perform this action!');
        }
            $validatedData = ProductController::validation($request);
            $product = new Product();
            $product->user_id = Auth::id();
            $product->title = $validatedData['title'];
            $product->size_id = $validatedData['size'];
            $product->price = bcdiv($validatedData['price'], 1, 2);
               $slug = Str::slug($validatedData['title'], '-');
        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . rand(1, 99) . rand(1, 99);
        }
              $product->slug = $slug;
            if (!empty($validatedData['sale_price'])) {
                if ($validatedData['price'] < $validatedData['sale_price']) {
                    throw ValidationException::withMessages(['sale_price' => 'Sale Price should be less than actual price']);
                }
                $product->sale_price = bcdiv($validatedData['sale_price'], 1, 2);
            }
            $product->status = 'drafted';
            if (Brand::whereId($validatedData['brand'])->exists()) {
                $product->brand_id = $validatedData['brand'];
            } else {
                return redirect()->back()->with('error', 'This brand doesnot exists');
            }
            $product->description = $validatedData['description'];
            $product->save();
            $categories = $request['category'];
            self::addCategories($product, $categories);
            $colors = $request['color'];
            self::attachColors($product, $colors);
            $followers = Follower::whereUserId(Auth::id())->get();
            if ($followers->isNotEmpty()) {
                foreach ($followers as $follower) {
                    NotificationController::newProductFollowerNotification(User::find($follower['follower_id']), $product);
                }
            }
        return redirect(route('products.edit', ['user' => Auth::user()->username, 'product' => $product->slug]))->with('info', 'Your product has been created and in draft mode. Please attach images to publish it.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(User $user, Product $product)
    {
        $id = $product['id'];
        if (self::productBelongsToUser($user, $id) === true) {
            if ($product->status !== 'drafted' || self::isProductOwner($user)) {
                $categories = $product->categories;
                $categoriesIds = [];
                foreach ($categories as $category) {
                    $categoriesIds[] = $category['id'];
                }
                $relatedProducts = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                    ->whereIn('category_id', $categoriesIds)
                    ->where('products.status', '=', 'published')
                    ->where('products.id', '!=', $product['id'])
                    ->distinct()
                    ->limit(4)
                    ->select('products.*')
                    ->get()->unique('id');
                return view('profile.products.show', ['product' => $product, 'user' => $user, 'relatedProducts' => $relatedProducts]);
            }
            return redirect()->back()->with('error', 'You can not view product that is in drafted mode');
        }
        return redirect()->back()->with('error', 'This product does not belongs to ' . $user->username);
    }


    /**
     * Get shipping information to create order
     *
     * @param User $user
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function orderShipping(User $user, Product $product)
    {
        $id = $product['id'];
        if (self::productBelongsToUser($user, $id) === true) {
            if ($product->status !== 'drafted') {
                return view('profile.products.orderShipping', ['product' => $product, 'user' => $user]);
            }
            return redirect()->back()->with('error', 'You can not view product that is in drafted mode');
        }
        return redirect()->back()->with('error', 'This product does not belongs to ' . $user->username);
    }

    /**
     * Confirm the order details
     *
     * @param User $user
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function orderConfirm(User $user, Product $product)
    {
        $id = $product['id'];
        if (self::productBelongsToUser($user, $id) === true) {
            if ($product->status !== 'drafted') {
                return view('profile.products.orderConfirm', ['product' => $product, 'user' => $user]);
            }
            return redirect()->back()->with('error', 'You can not view product that is in drafted mode');
        }
        return redirect()->back()->with('error', 'This product does not belongs to ' . $user->username);
    }



    /**
     * Show the form for editing the specified resource.
     * @param User $user
     * @param int $id
     */
    public function edit(User $user, Product $product)
    {
        $id = $product['id'];
        if (self::productBelongsToUser($user, $id) === false || self::isProductOwner($user) === false) {
            return redirect()->back()->with('error', 'You\'re not authorized to perform this action!');
        }
        $user = Auth::user();
        $product = Product::find($id);
        $productImages = $product->files;
        $images = [];
        foreach ($productImages as $productImage) {
            $images[] = ['path' => FileStorage::getUrl($productImage->path), 'id' => $productImage->id];
        }
        return view('profile.products.edit', ['product' => $product, 'brands' => Brand::all(), 'colors' => Color::all(), 'categories' => Category::all(), 'colors' => Color::all(), 'productImages' => $images, 'productCategories' => $product->categories, 'user' => $user, 'sizes' => Size::all()]);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @param int $id
     * @throws ValidationException
     */
    public function update(Request $request, User $user,Product $product)
    {
        $id = $product['id'];
        if (self::productBelongsToUser($user, $id) === false || self::isProductOwner($user) === false) {
            return redirect()->back()->with('error', 'You\'re not authorized to perform this action!');
        }

        $productCurrentStatus = $product->status;
        $validatedData = ProductController::validation($request);
        $product->user_id = Auth::id();
        $product->title = $validatedData['title'];
        $product->size_id = $validatedData['size'];
        $product->price = bcdiv($validatedData['price'], 1, 2);
        if (!empty($validatedData['sale_price'])) {
            if ($validatedData['price'] < $validatedData['sale_price']) {
                throw ValidationException::withMessages(['sale_price' => 'Sale Price should be less than actual price']);
            }
        }
        $productFiles = $product->files;
        if ($productFiles->isEmpty() && $request['status'] === 'published') {
            throw ValidationException::withMessages(['file' => 'Product images is required']);
        }
        $product->status = $request['status'];
        if (Brand::whereId($validatedData['brand'])->exists()) {
            $product->brand_id = $validatedData['brand'];
        } else {
            return redirect()->back()->with('error', 'This brand does not exists');
        }
        $product->description = $validatedData['description'];
        $product->save();
        self::deleteCategories($product);
        $categories = $request['category'];
        self::addCategories($product, $categories);
        self::detachColors($product);
        $colors = $request['color'];
        self::attachColors($product, $colors);
        $productLink = route('products.show', ['user' => $user->username, 'product' => $product->slug]);
            if ($request['status'] === 'drafted') {
                session()->flash('success', 'Your product is in now draft mode. It will not appear to public users.');
            } else if ($productCurrentStatus === 'drafted') {
                session()->flash('success', 'Your Product is now updated and published successfully. <a target="_blank" href="' . $productLink . '">View Product</a>');
            } else {
                session()->flash('success', 'Your Product has been updated successfully. <a target="_blank" href="' . $productLink . '">View Product</a>');
            }
            return redirect()->back();
        }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     */

    public function destroy(User $user, Product $product)
    {
        if (self::isProductOwner($user) == true && self::productBelongsToUser($user, $product['id']) == true) {
            Like::where('product_id', $product['id'])->delete();
            Share::where('product_id', $product['id'])->delete();
            self::deleteCategories($product);
            File::where('resource_id', $product['id'])->where('resource_name', 'product')->delete();
            Offer::where('product_id', $product['id'])->delete();
            Review::where('product_id', $product['id'])->delete();
            self::detachColors($product);
            $product->delete();
            return redirect('/profile/' . $user['username'] . '/products')->with('info', 'Your product has successfully been deleted');
        }
        return redirect()->back()->with('error', 'You have not access to perform this action');

    }

    public static function isProductOwner(User $user)
    {
        if (!(Auth::user() && Auth::id() == $user->id)) {
            return false;
        }

        return true;
    }

    public static function productBelongsToUser(User $user, $id)
    {
        if (Product::whereUserId($user->id)->whereId($id)->exists()) {
            return true;
        }
        return false;
    }

    public static function addCategories(Product $product, $categories)
    {
        if (count($categories) > 1) {
            foreach ($categories as $category) {
                if (Category::whereId($category)->exists()) {
                    $category = Category::whereId($category)->first();
                    $product->categories()->attach($category);
                } else {
                    return redirect()->back()->with('error', 'Category to which you are attaching product is not found');
                }
            }
        } elseif (Category::whereId($categories)->exists()) {
            $category = Category::whereId($categories)->first();
            $product->categories()->attach($category);
        } else {
            return redirect()->back()->with('error', 'Category to which you are attaching product is not found');
        }
    }

    public static function deleteCategories(Product $product)
    {
        $productCategories = $product->categories;
        if ($productCategories->isNotEmpty()) {
            foreach ($product->categories as $del) {
                $del->pivot->delete();
            }
        } else {
            return redirect()->back()->with('error', 'This product does not belongs to any category');
        }
    }


    public static function attachColors(Product $product, $colors)
    {
        if (count($colors) > 1) {
            foreach ($colors as $color) {
                if (Color::whereId($color)->exists()) {
                    $color = Color::whereId($color)->first();
                    $product->colors()->attach($color);
                } else {
                    return redirect()->back()->with('error', 'Color to which you are attaching product is not found');
                }
            }
        } else if (Color::whereId($colors)->exists()) {
            $color = Color::whereId($colors)->first();
            $product->colors()->attach($color);
        } else {
            return redirect()->back()->with('error', 'Color to which you are attaching product is not found');
        }
    }

    public static function detachColors(Product $product)
    {
        $productColors = $product->colors;
        if ($productColors->isNotEmpty()) {
            foreach ($product->colors as $del) {
                $del->pivot->delete();
            }
        } else {
            return redirect()->back()->with('error', 'This product has not any attached colors');
        }
    }

    public static function validation(Request $request)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|string',
                'size' => 'required|integer',
                'color' => 'required|array',
                'price' => 'required|numeric',
                'sale_price' => 'numeric|nullable',
                'brand' => 'required|int',
                'category' => 'required|array',
                'description' => 'required|string'
            ]
        );
        return $validatedData;
    }
}
