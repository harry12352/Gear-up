<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RecentlyViewedProductsController extends Controller
{
    public function push(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product === null) {
            return response()->json(404);
        }
        $user = $product->user;
        if (Auth::id() == $user->id) {
            return;
        }
        if (!($request->cookie('PI'))) {
            $productIds = [];
        } else {
            $productIds = json_decode($request->cookie('PI'));
        }

        if (count($productIds) > 0) {
            foreach ($productIds as $productId) {
                if ($productId == (int)$id) {
                    return true;
                }
            }
        }

        if (count($productIds) < 5) {
            $productIds[] = (int)$id;
        } else {
            array_unshift($productIds, (int)$id);
            array_pop($productIds);
        }
        $arrayJson = json_encode($productIds);
        $minutes = time() + 10 * 365 * 24 * 60 * 60;
        $response = new Response('');
        $response->withCookie(cookie('PI', $arrayJson, $minutes));
        return $response;
    }

    public function pull(Request $request)
    {
        $productIds = json_decode($request->cookie('PI'));
        $products = [];
        if ($productIds && count($productIds) > 0) {
            foreach ($productIds as $productId) {
                $product = Product::whereId($productId)->whereStatus('published')->first();
                if ($product) {
                    $productFiles = $product->files;
                    $productUser = User::whereId($product->user_id)->get()->first();
                    $product['price'] = '$' . number_format($product['price'], 2);
                    $product['url'] = route('products.show', ['user' => $productUser->username, 'product' => $product->slug]);
                    if ($productFiles && count($productFiles) > 0) {
                        $product['image'] = asset('storage/' . $productFiles[0]->path);
                    } else {
                        $product['image'] = null;
                    }
                    $products[] = $product;
                }
            }
            return response()->json(array_reverse($products));
        }
    }
}
