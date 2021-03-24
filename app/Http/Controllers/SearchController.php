<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $search = implode('%', str_split(str_replace(' ', '', $query)));
        $fuzzySearch = "%$search%";

        $products = Product::where('title', 'LIKE', $fuzzySearch)->get();
        if (empty($query)) {
            $products = [];
        }
        return view('profile.products.search-result', ['products' => $products]);
    }
}
