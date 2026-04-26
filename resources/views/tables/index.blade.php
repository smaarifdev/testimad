@extends('layouts.app')
@section('title', 'Tables')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Tables</h1>
            <p class="text-sm text-slate-500">Click a table to take an order.</p>
        </div>
        <button onclick="document.getElementById('add-table').classList.toggle('hidden')"
                class="bg-slate-900 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded">
            + Add Table
        </button>
    </div>

    <form id="add-table" action="{{ route('tables.store') }}" method="POST"
          class="hidden bg-white border rounded p-4 mb-6 flex gap-3 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-xs uppercase tracking-wide text-slate-500 mb-1">Table number</label>
            <input name="number" required class="w-full border rounded px-3 py-2 text-sm" placeholder="e.g. T-09">
        </div>
        <div class="w-32">
            <label class="block text-xs uppercase tracking-wide text-slate-500 mb-1">Capacity</label>
            <input name="capacity" type="number" min="1" max="50" value="4"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded">
            Create
        </button>
    </form>

    @if ($tables->isEmpty())
        <div class="bg-white border border-dashed border-slate-300 rounded p-12 text-center text-slate-500">
            No tables yet. Add one above to get started.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($tables as $table)
                @php
                    $hasOrder = $table->openOrder !== null;
                    $statusColor = $hasOrder
                        ? 'bg-amber-50 border-amber-400 hover:bg-amber-100'
                        : 'bg-emerald-50 border-emerald-400 hover:bg-emerald-100';
                @endphp
                <a href="{{ route('tables.show', $table) }}"
                   class="block border-2 {{ $statusColor }} rounded-lg p-5 transition shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="text-2xl font-bold">{{ $table->number }}</div>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $hasOrder ? 'bg-amber-500 text-white' : 'bg-emerald-500 text-white' }}">
                            {{ $hasOrder ? 'Occupied' : 'Free' }}
                        </span>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">Seats {{ $table->capacity }}</div>
                    @if ($hasOrder)
                        <div class="mt-2 text-sm font-semibold text-amber-700">
                            ${{ number_format($table->openOrder->total, 2) }}
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
@endsection
