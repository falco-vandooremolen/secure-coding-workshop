<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->input('q');

        $products = DB::select("SELECT * FROM products WHERE name LIKE '%" . $term . "%'");

        return view('search.index', ['products' => $products]);
    }
}
