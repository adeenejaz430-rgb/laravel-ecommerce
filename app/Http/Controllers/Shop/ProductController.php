<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Product listing page
     * - search
     * - category filter
     * - price range
     * - sorting
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        // --- read filters from request ---
        $search       = $request->input('search');
        $categorySlug = $request->input('category');
        $minPrice     = $request->input('min_price');
        $maxPrice     = $request->input('max_price');
        $sort         = $request->input('sort', 'newest');

        // --- search by name / description ---
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // --- filter by category (slug or id) ---
        if (!empty($categorySlug)) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->orWhere('id', $categorySlug);
            });
        }

        // --- price range ---
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }

        // --- sorting: newest / price_asc / price_desc ---
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 👇 This is what your Blade expects
        $filteredProducts = $query->paginate(12)->withQueryString();

        // For "X of Y products" counter in Blade
        $products = Product::all();

        // For sidebar / navbar
        $categories = Category::orderBy('name')->get();

        return view('shop.products.index', [
            'filteredProducts' => $filteredProducts,
            'products'         => $products,
            'categories'       => $categories,
            'sort'             => $sort,
            'search'           => $search,
            'categorySlug'     => $categorySlug,
            'minPrice'         => $minPrice,
            'maxPrice'         => $maxPrice,
        ]);
    }

    /**
     * Show single product
     * Make sure your route is like:
     * Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
     */
    public function show(Product $product)
    {
        // Optional: increment views for "popular products"
        $product->increment('views');

        // Related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(8)
            ->get();

        // Navbar categories
        $categories = Category::orderBy('name')->get();

        return view('shop.products.show', [
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
            'categories'      => $categories,
        ]);
    }

    /**
     * Products for a specific category (SEO-friendly).
     * Route example:
     * Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('category.show');
     */
    public function category(Category $category, Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'newest');

        $query = Product::where('category_id', $category->id);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('shop.categories.show', [
            'category'   => $category,
            'products'   => $products,
            'categories' => $categories,
            'search'     => $search,
            'sort'       => $sort,
        ]);
    }
}
