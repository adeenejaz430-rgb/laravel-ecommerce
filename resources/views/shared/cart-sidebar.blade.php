{{-- resources/views/shared/cart-sidebar.blade.php --}}
{{-- Suggestion: convert this to a Livewire component later for full interactivity --}}
{{-- Placeholder markup if you decide to keep a sidebar UI --}}
<div
    id="cart-sidebar"
    class="hidden fixed right-0 top-0 h-full w-full sm:w-[420px] bg-gradient-to-b from-white to-gray-50 shadow-2xl z-50 flex flex-col"
>
    {{-- Header --}}
    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                🛍
            </div>
            <div>
                <h2 class="text-2xl font-bold">Your Cart</h2>
                <p class="text-sm text-green-100">
                    {{-- you can inject total items with JS or Livewire --}}
                </p>
            </div>
        </div>
        <button
            type="button"
            class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center"
            onclick="document.getElementById('cart-sidebar').classList.add('hidden')"
        >
            ✕
        </button>
    </div>

    {{-- Body: for now, show a simple message --}}
    <div class="flex-1 overflow-y-auto p-6">
        <p class="text-gray-500">
            Implement cart sidebar as a Livewire/JS component using your Cart model/session.
        </p>
    </div>
</div>
