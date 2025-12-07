<header class="fixed w-full z-50 transition-all duration-300 bg-white border-b border-gray-100 shadow-sm">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            {{-- LOGO --}}
            <a href="{{ route('shop.home') }}" class="flex items-center z-50">
                <div class="relative h-10 w-32 sm:h-12 sm:w-36 lg:h-16 lg:w-48">
                    <img src="/logo.png" alt="Logo" class="h-full w-full object-contain">
                </div>
            </a>

            @php
                use Illuminate\Support\Facades\Route;

                $user      = auth()->user();
                $isAuthed  = (bool) $user;
                $current   = url()->current();

                $navLinks = [
                    ['name' => 'Home',     'route' => 'shop.home',      'href' => '/'],
                    ['name' => 'Products', 'route' => 'products.index', 'href' => '/products'],
                    ['name' => 'About',    'route' => null,             'href' => '/about'],
                    ['name' => 'Blog',     'route' => null,             'href' => '/blog'],
                    ['name' => 'Contact',  'route' => null,             'href' => '/contact'],
                ];

                $categoriesList = $categories ?? [];
                $cartCount      = $cartCount ?? 0;
                $wishlistCount  = $wishlistCount ?? 0;
            @endphp

            {{-- DESKTOP NAV --}}
            <nav class="hidden lg:flex items-center space-x-8">

                {{-- MAIN LINKS --}}
                @foreach($navLinks as $link)
                    @php
                        $href = (!empty($link['route']) && Route::has($link['route']))
                                    ? route($link['route'])
                                    : url($link['href']);

                        $active = ($current === $href);
                    @endphp

                    <a href="{{ $href }}"
                       class="text-base font-medium transition-colors relative pb-1
                              {{ $active ? 'text-green-600' : 'text-gray-700 hover:text-green-600' }}">
                        {{ $link['name'] }}

                        @if($active)
                            <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-green-600"></span>
                        @endif
                    </a>
                @endforeach

                {{-- CATEGORIES DROPDOWN --}}
                @if(count($categoriesList))
                    <div class="relative group">
                        <button class="text-base font-medium text-gray-700 hover:text-green-600 pb-1">
                            Categories
                        </button>

                        <div
                            class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg opacity-0 invisible 
                                   group-hover:opacity-100 group-hover:visible transition-all duration-200 
                                   border border-gray-100 z-50"
                        >
                            @foreach($categoriesList as $cat)
                                @php
                                    $slug = $cat->slug ?? $cat['slug'];
                                    $categoryHref = Route::has('category.show')
                                        ? route('category.show', $slug)
                                        : '#';
                                @endphp

                                <a href="{{ $categoryHref }}"
                                   class="block px-4 py-2 text-sm text-gray-700 
                                          hover:bg-green-50 hover:text-green-600">
                                    {{ $cat->name ?? $cat['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </nav>

            {{-- RIGHT SIDE --}}
            <div class="flex items-center space-x-4">

                {{-- SEARCH --}}
                <form action="{{ route('products.index') }}" method="GET"
                      class="hidden lg:flex items-center border-2 border-green-600 rounded-full overflow-hidden">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search products..."
                           class="px-4 py-2 w-64 focus:outline-none">
                    <button type="submit" class="w-12 h-12 bg-green-600 text-white flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">search</span>
                    </button>
                </form>

                {{-- CART --}}
                @if($isAuthed)
                    <a href="/cart"
                       class="relative w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center 
                              rounded-full bg-green-600 text-white">
                        <span class="material-symbols-outlined text-xl">shopping_cart</span>

                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold
                                         rounded-full h-5 w-5 flex items-center justify-center border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif

                {{-- USER DROPDOWN --}}
                @if($isAuthed)
                    <div class="hidden lg:block relative group/user">

                        {{-- IMPORTANT: <div> instead of <button> --}}
                        <div class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center
                                    rounded-full bg-green-600 text-white cursor-pointer">
                            <span class="material-symbols-outlined text-xl">person</span>
                        </div>

                        {{-- DROPDOWN --}}
                        <div
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100
                                   opacity-0 invisible group/user-hover:opacity-100 group/user-hover:visible 
                                   transition-all duration-200 py-2 z-50 pointer-events-auto"
                        >

                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>

                            <a href="{{ route('profile.index') }}"
                               class="block px-4 py-2.5 text-sm hover:bg-green-50">
                                My Profile
                            </a>

                            <a href="{{ route('orders.index') }}"
                               class="block px-4 py-2.5 text-sm hover:bg-green-50">
                                My Orders
                            </a>

                            <a href="{{ route('wishlist.index') }}"
                               class="px-4 py-2.5 text-sm hover:bg-green-50 flex justify-between">
                                Wishlist
                                @if($wishlistCount > 0)
                                    <span class="bg-green-600 text-white text-xs rounded-full h-5 w-5 
                                                 flex items-center justify-center">
                                        {{ $wishlistCount }}
                                    </span>
                                @endif
                            </a>

                            @if(isset($user->role) && $user->role === 'admin' && Route::has('admin.dashboard'))
                                <a href="{{ route('admin.dashboard') }}"
                                   class="block px-4 py-2.5 text-sm hover:bg-green-50">
                                    Admin Dashboard
                                </a>
                            @endif

                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50"
                                    >
                                        Sign Out
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden lg:block">
                        <button class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-green-600 
                                       text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">person</span>
                        </button>
                    </a>
                @endif

                {{-- MOBILE MENU BUTTON --}}
                <button
                    class="lg:hidden border-2 border-green-600 text-green-600 w-10 h-10 rounded-full 
                           flex items-center justify-center z-50"
                >
                    <span class="material-symbols-outlined">menu</span>
                </button>

            </div>

        </div>
    </div>
</header>
