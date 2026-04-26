@extends('layouts.app')
@section('title', 'Table ' . $table->number)

@section('content')
    {{-- Breadcrumb / header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('tables.index') }}" class="text-xs uppercase tracking-[0.2em] text-copper hover:text-ember inline-flex items-center gap-1.5 mb-3">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Floor
            </a>
            <h1 class="font-display text-5xl font-bold leading-none">
                Table <span class="text-ember">{{ $table->number }}</span>
            </h1>
            <p class="text-cream/50 text-sm mt-2">
                Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · opened {{ $order->created_at->diffForHumans() }} · seats {{ $table->capacity }}
            </p>
        </div>
        <div class="hidden md:flex items-center gap-3 bg-coal/60 ring-1 ring-white/5 rounded-full px-5 py-2.5">
            <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-ember opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-ember"></span>
            </span>
            <span class="text-xs uppercase tracking-[0.2em] text-cream/70">Live Service</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        {{-- Menu (3/5) --}}
        <div class="lg:col-span-3 space-y-10">
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-copper/40"></div>
                <h2 class="font-display text-2xl italic text-copper">Carte du Jour</h2>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-copper/40"></div>
            </div>

            @forelse ($products as $category => $items)
                <div>
                    <div class="flex items-baseline justify-between mb-4">
                        <h3 class="font-display text-xl capitalize">{{ $category }}</h3>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-cream/30">{{ $items->count() }} items</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($items as $product)
                            <form action="{{ route('orders.items.add', $order) }}" method="POST" class="group">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full text-left bg-coal/60 hover:bg-coal ring-1 ring-white/5 hover:ring-ember/50 rounded-xl p-4 transition-all hover:-translate-y-0.5 hover:shadow-glow-amber">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="font-display font-semibold text-lg leading-tight text-cream">{{ $product->name }}</div>
                                            @if ($product->description)
                                                <div class="text-xs text-cream/50 mt-1 line-clamp-2">{{ $product->description }}</div>
                                            @endif
                                        </div>
                                        <div class="font-display font-bold text-ember text-lg whitespace-nowrap">
                                            ${{ number_format($product->price, 2) }}
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center text-[10px] uppercase tracking-[0.2em] text-copper/0 group-hover:text-copper transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add to order
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-coal/50 border border-dashed border-white/10 rounded-2xl p-12 text-center text-cream/40">
                    The kitchen is bare. Add items in the
                    <a href="{{ route('products.index') }}" class="text-ember underline">Menu</a>.
                </div>
            @endforelse
        </div>

        {{-- Receipt-style order panel (2/5) --}}
        <div class="lg:col-span-2">
            <div class="sticky top-6">
                <div class="ticket rounded-t-sm shadow-2xl">
                    {{-- Receipt header --}}
                    <div class="px-6 pt-6 pb-4 text-center border-b border-dashed border-ink/20">
                        <div class="font-display font-bold text-2xl tracking-tight">testimad</div>
                        <div class="text-[10px] uppercase tracking-[0.3em] text-ink/60 mt-1">— Order Ticket —</div>
                        <div class="flex items-center justify-between text-[11px] text-ink/60 mt-4 font-mono">
                            <span>#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <span>Table {{ $table->number }}</span>
                            <span>{{ $order->created_at->format('H:i') }}</span>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="px-6 py-4 min-h-[120px]">
                        @if ($order->items->isEmpty())
                            <div class="text-center py-12 text-ink/40 text-sm italic font-display">
                                No items yet.<br>Tap a dish to begin.
                            </div>
                        @else
                            <ul class="space-y-3">
                                @foreach ($order->items as $item)
                                    <li class="flex items-start gap-3 group">
                                        <form action="{{ route('orders.items.update', [$order, $item]) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input name="quantity" type="number" min="1" max="99" value="{{ $item->quantity }}"
                                                   onchange="this.form.submit()"
                                                   class="w-12 bg-transparent border-0 border-b border-ink/20 focus:border-ink focus:outline-none text-center font-mono font-bold text-ink py-0.5">
                                        </form>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-sm leading-tight">{{ $item->product->name }}</div>
                                            <div class="text-[11px] text-ink/50 font-mono">@ ${{ number_format($item->unit_price, 2) }}</div>
                                        </div>
                                        <div class="font-mono font-bold text-sm">${{ $item->subtotal }}</div>
                                        <form action="{{ route('orders.items.remove', [$order, $item]) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-ink/30 hover:text-wine opacity-0 group-hover:opacity-100 transition text-lg leading-none">
                                                ×
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Totals --}}
                    <div class="px-6 py-4 border-t border-dashed border-ink/20 space-y-1.5">
                        <div class="flex justify-between text-xs text-ink/60 font-mono">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-ink/60 font-mono">
                            <span>Service (incl.)</span>
                            <span>—</span>
                        </div>
                        <div class="flex justify-between font-display font-bold text-2xl pt-2 mt-2 border-t border-ink/30">
                            <span>Total</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="text-center text-[10px] uppercase tracking-[0.3em] text-ink/40 pb-4">
                        ✦ Merci ✦
                    </div>
                </div>

                {{-- Torn/perforated bottom edge --}}
                <div class="h-3 bg-cream ticket-edge -mt-px"
                     style="background-image: radial-gradient(circle at 8px 100%, #15110d 0, #15110d 6px, transparent 7px); background-size: 16px 12px; background-position: 0 100%; background-repeat: repeat-x;"></div>

                @if ($order->items->isNotEmpty())
                    <form action="{{ route('orders.close', $order) }}" method="POST" class="mt-4">
                        @csrf
                        <button class="w-full bg-gradient-to-r from-ember to-copper hover:from-copper hover:to-ember text-ink font-bold py-4 rounded-xl shadow-lg transition flex items-center justify-center gap-2 font-display text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Close & Settle Bill
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
