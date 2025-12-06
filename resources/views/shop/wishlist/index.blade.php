@extends('layouts.store')

@section('title', 'Wishlist')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            ← Back to Products
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-8">My Wishlist</h1>

    @if($items->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <div class="w-16 h-16 mx-auto bg-gray-200 rounded-full flex items-center justify-center mb-4">
                ❤️
            </div>
            <h2 class="text-xl font-medium mb-2">Your wishlist is empty</h2>
            <p class="text-gray-500 mb-6">
                Save items you love for later by clicking the heart icon on product pages.
            </p>
            <a href="{{ route('products.index') }}">
                <button class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Browse Products
                </button>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="aspect-w-3 aspect-h-2 bg-gray-200">
                        @if($item->image)
                            <img
                                src="{{ $item->image }}"
                                alt="{{ $item->name }}"
                                class="w-full h-full object-cover"
                            />
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-medium mb-2">{{ $item->name }}</h3>
                        <p class="text-blue-600 font-bold mb-4">${{ number_format($item->price, 2) }}</p>
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('cart.add', $item->product_id ?? $item->id) }}" class="flex-1">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full flex items-center justify-center bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700"
                                >
                                    🛒 Add to Cart
                                </button>
                            </form>
                            <form method="POST" action="{{ route('wishlist.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="p-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200"
                                    aria-label="Remove from wishlist"
                                >
                                    🗑
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
