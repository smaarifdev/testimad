<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('category')->orderBy('name')->get();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:60',
            'available' => 'sometimes|boolean',
        ]);

        $data['available'] = $request->boolean('available', true);

        Product::create($data);

        return redirect()->route('products.index')->with('status', 'Product added.');
    }

    public function toggle(Product $product)
    {
        $product->update(['available' => ! $product->available]);
        return back();
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('status', 'Product removed.');
    }
}
