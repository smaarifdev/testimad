@extends('layouts.app')
@section('title', 'Menu')

@section('content')
    <div class="flex items-end justify-between mb-10 pb-6 border-b border-white/5">
        <div>
            <div class="text-copper text-xs uppercase tracking-[0.3em] mb-2">The Kitchen</div>
            <h1 class="font-display text-5xl font-bold leading-none">Le Menu</h1>
            <p class="text-cream/50 text-sm mt-3 max-w-md">
                {{ $products->where('available', true)->count() }} of {{ $products->count() }} dishes on offer tonight.
            </p>
        </div>
        <button onclick="document.getElementById('add-product').classList.toggle('hidden')"
                class="group flex items-center gap-2 bg-cream text-ink px-5 py-3 rounded-full font-semibold text-sm hover:bg-ember hover:text-cream transition shadow-lg">
            <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Dish
        </button>
    </div>

    <form id="add-product" action="{{ route('products.store') }}" method="POST"
          class="hidden bg-coal/80 ring-1 ring-white/10 rounded-2xl p-6 mb-10 grid grid-cols-1 md:grid-cols-6 gap-4">
        @csrf
        <div class="md:col-span-3">
            <label class="block text-[10px] uppercase tracking-[0.2em] text-copper mb-2">Dish Name</label>
            <input name="name" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-sm focus:border-ember focus:outline-none">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] uppercase tracking-[0.2em] text-copper mb-2">Category</label>
            <input name="category" required value="main"
                   list="cat-list"
                   class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-sm focus:border-ember focus:outline-none">
            <datalist id="cat-list">
                <option value="starter"><option value="main"><option value="side"><option value="dessert"><option value="drink">
            </datalist>
        </div>
        <div>
            <label class="block text-[10px] uppercase tracking-[0.2em] text-copper mb-2">Price</label>
            <input name="price" type="number" step="0.01" min="0" required
                   class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-sm focus:border-ember focus:outline-none">
        </div>
        <div class="md:col-span-5">
            <label class="block text-[10px] uppercase tracking-[0.2em] text-copper mb-2">Description</label>
            <textarea name="description" rows="2"
                      class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-sm focus:border-ember focus:outline-none"></textarea>
        </div>
        <div class="md:col-span-1 flex items-end">
            <button class="w-full bg-ember hover:bg-copper text-ink font-semibold px-5 py-3 rounded-lg text-sm transition">
                Save
            </button>
        </div>
    </form>

    @php
        $grouped = $products->groupBy('category');
    @endphp

    @if ($products->isEmpty())
        <div class="bg-coal/50 border border-dashed border-white/10 rounded-2xl p-16 text-center text-cream/40">
            <div class="text-5xl mb-4 opacity-30">🍽️</div>
            <p>No dishes on the menu yet.</p>
        </div>
    @else
        <div class="space-y-12">
            @foreach ($grouped as $category => $items)
                <section>
                    <div class="flex items-baseline gap-4 mb-5">
                        <h2 class="font-display text-3xl italic capitalize text-copper">{{ $category }}</h2>
                        <div class="h-px flex-1 bg-gradient-to-r from-copper/40 to-transparent"></div>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-cream/30">{{ $items->count() }} dishes</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($items as $product)
                            <div class="group relative bg-coal/60 ring-1 ring-white/5 rounded-xl p-5 hover:ring-copper/40 transition
                                {{ $product->available ? '' : 'opacity-50' }}">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <h3 class="font-display font-semibold text-lg leading-tight">{{ $product->name }}</h3>
                                    <div class="font-display font-bold text-ember text-lg whitespace-nowrap">
                                        ${{ number_format($product->price, 2) }}
                                    </div>
                                </div>

                                @if ($product->description)
                                    <p class="text-xs text-cream/50 leading-relaxed mb-4">{{ $product->description }}</p>
                                @else
                                    <div class="mb-4"></div>
                                @endif

                                <div class="flex items-center justify-between pt-3 border-t border-white/5">
                                    <form action="{{ route('products.toggle', $product) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="text-[10px] uppercase tracking-[0.2em] flex items-center gap-1.5
                                            {{ $product->available ? 'text-emerald-400' : 'text-cream/30' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $product->available ? 'bg-emerald-400 animate-pulse-slow' : 'bg-cream/30' }}"></span>
                                            {{ $product->available ? 'On Menu' : 'Off Menu' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                          onsubmit="return confirm('Remove “{{ $product->name }}” from the menu?')">
                                        @csrf @method('DELETE')
                                        <button class="text-cream/30 hover:text-wine transition opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
@endsection
