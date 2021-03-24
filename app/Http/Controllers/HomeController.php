<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $base = Product::where('status', '=', 'published');
        return view('home',
            [
                'categories' => Category::all()->slice(0, 8),
                'products' => $base->paginate(12),
                'sizes' => Size::all()
            ]
        );
    }

}
