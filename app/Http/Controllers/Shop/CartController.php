<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        // simple session-based cart
        $cart = session()->get('cart', []);

        $existingQty = $cart[$product->id]['qty'] ?? 0;

        $cart[$product->id] = [
            'id'    => $product->id,
            'name'  => $product->name,
            'price' => $product->price,
            'qty'   => $existingQty + 1,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart.');
    }
}
