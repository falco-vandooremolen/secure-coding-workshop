<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'unit_price' => $request->input('price'),
            'total' => $request->input('price') * $request->input('quantity'),
        ]);

        return response()->json($order, 201);
    }
}
