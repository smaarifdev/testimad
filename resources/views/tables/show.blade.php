@extends('layouts.app')
@section('title', 'Table ' . $table->number)

@section('content')
    <div class="mb-6">
        <a href="{{ route('tables.index') }}" class="text-sm text-slate-500 hover:text-slate-800">← All tables</a>
        <h1 class="text-2xl font-bold mt-1">Table {{ $table->number }}</h1>
        <p class="text-sm text-slate-500">Order #{{ $order->id }} · opened {{ $order->created_at->diffForHumans() }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Menu --}}
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-lg font-semibold">Menu</h2>

            @forelse ($products as $category => $items)
                <div>
                    <h3 class="text-xs uppercase tracking-wider text-slate-500 mb-2">{{ $category }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($items as $product)
                            <form action="{{ route('orders.items.add', $order) }}" method="POST"
                                  class="bg-white border rounded p-3 hover:border-amber-400 hover:shadow transition text-left">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full text-left">
                                    <div class="font-medium text-sm">{{ $product->name }}</div>
                                    @if ($product->description)
                                        <div class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $product->description }}</div>
                                    @endif
                                    <div class="mt-2 text-amber-700 font-semibold">${{ number_format($product->price, 2) }}</div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white border border-dashed rounded p-8 text-center text-slate-500">
                    No products yet. Add them on the <a href="{{ route('products.index') }}" class="text-amber-600 underline">Menu page</a>.
                </div>
            @endforelse
        </div>

        {{-- Current order --}}
        <div class="lg:col-span-1">
            <div class="bg-white border rounded shadow-sm sticky top-4">
                <div class="px-4 py-3 border-b">
                    <h2 class="text-lg font-semibold">Current Order</h2>
                </div>

                @if ($order->items->isEmpty())
                    <div class="p-8 text-center text-slate-400 text-sm">
                        No items. Tap a product to add.
                    </div>
                @else
                    <ul class="divide-y">
                        @foreach ($order->items as $item)
                            <li class="px-4 py-3 flex items-start gap-3">
                                <form action="{{ route('orders.items.update', [$order, $item]) }}" method="POST"
                                      class="flex items-center gap-1">
                                    @csrf @method('PATCH')
                                    <input name="quantity" type="number" min="1" max="99" value="{{ $item->quantity }}"
                                           onchange="this.form.submit()"
                                           class="w-14 border rounded px-2 py-1 text-sm text-center">
                                </form>
                                <div class="flex-1">
                                    <div class="text-sm font-medium">{{ $item->product->name }}</div>
                                    <div class="text-xs text-slate-500">${{ number_format($item->unit_price, 2) }} ea</div>
                                </div>
                                <div class="text-sm font-semibold">${{ $item->subtotal }}</div>
                                <form action="{{ route('orders.items.remove', [$order, $item]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-500 hover:text-rose-700 text-lg leading-none">&times;</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="px-4 py-3 border-t bg-slate-50">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                @if ($order->items->isNotEmpty())
                    <form action="{{ route('orders.close', $order) }}" method="POST" class="px-4 pb-4">
                        @csrf
                        <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 rounded">
                            Close & Pay
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
