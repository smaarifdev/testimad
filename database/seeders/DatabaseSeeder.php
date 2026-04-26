<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\RestaurantTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach (range(1, 8) as $i) {
            RestaurantTable::firstOrCreate(
                ['number' => 'T-' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                ['capacity' => $i % 2 === 0 ? 4 : 2]
            );
        }

        $products = [
            ['Margherita Pizza', 'Tomato, mozzarella, fresh basil', 12.50, 'main'],
            ['Spaghetti Carbonara', 'Pancetta, egg yolk, pecorino', 14.00, 'main'],
            ['Caesar Salad', 'Romaine, parmesan, anchovy dressing', 9.50, 'starter'],
            ['Bruschetta', 'Toasted bread, tomato, garlic', 7.00, 'starter'],
            ['Grilled Salmon', 'With lemon butter and asparagus', 18.50, 'main'],
            ['Beef Burger', 'House blend, cheddar, pickles', 13.00, 'main'],
            ['French Fries', 'Side of crispy fries', 4.50, 'side'],
            ['Tiramisu', 'Classic Italian dessert', 6.50, 'dessert'],
            ['Chocolate Lava Cake', 'Warm, with vanilla ice cream', 7.50, 'dessert'],
            ['Espresso', 'Single shot', 2.50, 'drink'],
            ['Cappuccino', 'Espresso with steamed milk', 3.50, 'drink'],
            ['House Red Wine', 'Glass, 175ml', 6.00, 'drink'],
            ['Sparkling Water', '500ml bottle', 3.00, 'drink'],
        ];

        foreach ($products as [$name, $desc, $price, $cat]) {
            Product::firstOrCreate(
                ['name' => $name],
                ['description' => $desc, 'price' => $price, 'category' => $cat]
            );
        }
    }
}
