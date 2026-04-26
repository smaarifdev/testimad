@extends('layouts.app')
@section('title', 'Floor')

@section('content')
    <div class="flex items-end justify-between mb-10 pb-6 border-b border-white/5">
        <div>
            <div class="text-copper text-xs uppercase tracking-[0.3em] mb-2">Tonight's Service</div>
            <h1 class="font-display text-5xl font-bold leading-none">The Floor</h1>
            <p class="text-cream/50 text-sm mt-3 max-w-md">
                {{ $tables->filter(fn($t) => $t->openOrder !== null)->count() }} of {{ $tables->count() }} tables seated · tap a table to open or continue an order.
            </p>
        </div>
        <button onclick="document.getElementById('add-table').classList.toggle('hidden')"
                class="group flex items-center gap-2 bg-cream text-ink px-5 py-3 rounded-full font-semibold text-sm hover:bg-ember hover:text-cream transition shadow-lg">
            <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Table
        </button>
    </div>

    <form id="add-table" action="{{ route('tables.store') }}" method="POST"
          class="hidden bg-coal/80 ring-1 ring-white/10 rounded-2xl p-6 mb-10 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        @csrf
        <div>
            <label class="block text-[10px] uppercase tracking-[0.2em] text-copper mb-2">Table Number</label>
            <input name="number" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-sm focus:border-ember focus:outline-none" placeholder="e.g. T-09">
        </div>
        <div>
            <label class="block text-[10px] uppercase tracking-[0.2em] text-copper mb-2">Capacity</label>
            <input name="capacity" type="number" min="1" max="50" value="4"
                   class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-sm focus:border-ember focus:outline-none">
        </div>
        <button class="bg-ember hover:bg-copper text-ink font-semibold px-5 py-3 rounded-lg text-sm transition">
            Create Table
        </button>
    </form>

    @if ($tables->isEmpty())
        <div class="bg-coal/50 border border-dashed border-white/10 rounded-2xl p-16 text-center text-cream/40">
            <div class="text-5xl mb-4 opacity-30">🪑</div>
            <p>The dining room awaits its first table.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach ($tables as $table)
                @php
                    $hasOrder = $table->openOrder !== null;
                @endphp
                <a href="{{ route('tables.show', $table) }}" class="group relative">
                    <div class="aspect-square relative">
                        {{-- Chair dots around the table --}}
                        @php $chairs = max(2, min(8, (int) $table->capacity)); @endphp
                        @for ($i = 0; $i < $chairs; $i++)
                            @php
                                $angle = ($i / $chairs) * 360;
                            @endphp
                            <div class="absolute w-3 h-3 rounded-full {{ $hasOrder ? 'bg-ember/60' : 'bg-cream/30' }} transition group-hover:scale-125"
                                 style="top: 50%; left: 50%; transform: rotate({{ $angle }}deg) translate(0, -50%) translate(0, -85px);"></div>
                        @endfor

                        {{-- The "table" --}}
                        <div class="absolute inset-6 rounded-full flex items-center justify-center transition-all
                            {{ $hasOrder
                                ? 'bg-gradient-to-br from-ember/30 to-wine/40 ring-2 ring-ember shadow-glow-amber'
                                : 'bg-gradient-to-br from-cream/5 to-cream/10 ring-1 ring-cream/20 group-hover:ring-cream/50 group-hover:shadow-glow-emerald' }}">

                            @if ($hasOrder)
                                <span class="absolute top-3 right-3 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-ember opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-ember"></span>
                                </span>
                            @endif

                            <div class="text-center">
                                <div class="font-display font-bold text-3xl leading-none {{ $hasOrder ? 'text-cream' : 'text-cream/80' }}">
                                    {{ $table->number }}
                                </div>
                                <div class="text-[9px] uppercase tracking-[0.2em] mt-2 {{ $hasOrder ? 'text-ember' : 'text-cream/40' }}">
                                    {{ $hasOrder ? 'Seated' : 'Available' }}
                                </div>
                                @if ($hasOrder)
                                    <div class="font-display text-base font-semibold mt-1.5 text-ember">
                                        ${{ number_format($table->openOrder->total, 2) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-[10px] uppercase tracking-[0.2em] text-cream/30 mt-2">
                        Seats {{ $table->capacity }}
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Legend --}}
    <div class="mt-12 flex items-center justify-center gap-8 text-xs text-cream/40">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-cream/30 ring-1 ring-cream/40"></span>
            Available
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-ember ring-1 ring-ember"></span>
            Seated · order open
        </div>
    </div>
@endsection
