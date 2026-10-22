<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate(['q' => 'required|string|max:100']);
        $term = $validated['q'];

        $products = DB::table('products')
            ->where('name', 'like', '%' . $term . '%')
            ->get();

        return view('search.index', ['products' => $products]);
    }
}
