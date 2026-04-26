@extends('layouts.app')
@section('title', 'Menu')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Menu</h1>
            <p class="text-sm text-slate-500">Manage the products available for orders.</p>
        </div>
        <button onclick="document.getElementById('add-product').classList.toggle('hidden')"
                class="bg-slate-900 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded">
            + Add Product
        </button>
    </div>

    <form id="add-product" action="{{ route('products.store') }}" method="POST"
          class="hidden bg-white border rounded p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
        @csrf
        <div class="md:col-span-2">
            <label class="block text-xs uppercase tracking-wide text-slate-500 mb-1">Name</label>
            <input name="name" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wide text-slate-500 mb-1">Category</label>
            <input name="category" required value="main" class="w-full border rounded px-3 py-2 text-sm"
                   placeholder="main, drink, dessert">
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wide text-slate-500 mb-1">Price</label>
            <input name="price" type="number" step="0.01" min="0" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded">
            Create
        </button>
        <div class="md:col-span-5">
            <label class="block text-xs uppercase tracking-wide text-slate-500 mb-1">Description (optional)</label>
            <textarea name="description" rows="2" class="w-full border rounded px-3 py-2 text-sm"></textarea>
        </div>
    </form>

    @if ($products->isEmpty())
        <div class="bg-white border border-dashed rounded p-12 text-center text-slate-500">
            No products yet.
        </div>
    @else
        <div class="bg-white border rounded shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="text-left px-4 py-3">Name</th>
                        <th class="text-left px-4 py-3">Category</th>
                        <th class="text-right px-4 py-3">Price</th>
                        <th class="text-center px-4 py-3">Available</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($products as $product)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $product->name }}</div>
                                @if ($product->description)
                                    <div class="text-xs text-slate-500">{{ $product->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs bg-slate-200 px-2 py-0.5 rounded">{{ $product->category }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('products.toggle', $product) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-xs px-2 py-1 rounded
                                        {{ $product->available ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                                        {{ $product->available ? 'Yes' : 'No' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete {{ $product->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-500 hover:text-rose-700 text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
