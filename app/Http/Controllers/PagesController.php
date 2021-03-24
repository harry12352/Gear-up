<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PagesController extends Controller
{
    public function view(Page $page)
    {
        return view('pages.show', ['page' => $page]);
    }
}
