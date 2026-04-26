<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class RestaurantTableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with('openOrder')->orderBy('number')->get();
        return view('tables.index', compact('tables'));
    }

    public function show(RestaurantTable $table)
    {
        $order = $table->openOrder()->with('items.product')->first();

        if (! $order) {
            $order = Order::create([
                'restaurant_table_id' => $table->id,
                'status' => 'open',
            ]);
            $table->update(['status' => 'occupied']);
            $order->load('items.product');
        }

        $products = Product::where('available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('tables.show', compact('table', 'order', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number' => 'required|string|max:50|unique:restaurant_tables,number',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        RestaurantTable::create($data);

        return redirect()->route('tables.index')->with('status', 'Table added.');
    }

    public function destroy(RestaurantTable $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('status', 'Table removed.');
    }
}
