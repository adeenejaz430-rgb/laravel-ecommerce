<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Show wishlist for logged-in user.
     */
    public function index()
    {
        $user = Auth::user();

        $items = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('shop.wishlist.index', compact('items'));
    }

    /**
     * Add product to wishlist.
     * Route example: POST /wishlist/{product}
     */
    public function store(Product $product, Request $request)
    {
        $user = Auth::user();

        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Product already in wishlist.');
        }

        Wishlist::create([
            'user_id'    => $user->id,
            'product_id' => $product->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Added to wishlist.']);
        }

        return back()->with('success', 'Product added to wishlist.');
    }

    /**
     * Remove product from wishlist.
     * Route example: DELETE /wishlist/{product}
     */
    public function destroy(Product $product, Request $request)
    {
        $user = Auth::user();

        Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Removed from wishlist.']);
        }

        return back()->with('success', 'Product removed from wishlist.');
    }
}
