<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function addItem(Request $request, Order $order)
    {
        abort_if($order->status !== 'open', 422, 'Order is not open.');

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;

        $existing = $order->items()->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->increment('quantity', $quantity);
        } else {
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ]);
        }

        $order->recalculateTotal();

        return back();
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        abort_if($item->order_id !== $order->id, 404);

        $item->delete();
        $order->recalculateTotal();

        return back();
    }

    public function updateItemQuantity(Request $request, Order $order, OrderItem $item)
    {
        abort_if($item->order_id !== $order->id, 404);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $item->update(['quantity' => $data['quantity']]);
        $order->recalculateTotal();

        return back();
    }

    public function close(Order $order)
    {
        abort_if($order->status !== 'open', 422);

        $order->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $order->table->update(['status' => 'available']);

        return redirect()->route('tables.index')->with('status', "Order #{$order->id} closed. Total: $" . number_format($order->total, 2));
    }
}
