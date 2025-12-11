<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(8)->sequence(
            ['name' => 'Canvas Tote Bag', 'price' => 24.00, 'stock_quantity' => 15],
            ['name' => 'Wireless Earbuds', 'price' => 79.00, 'stock_quantity' => 12],
            ['name' => 'Insulated Water Bottle', 'price' => 19.00, 'stock_quantity' => 25],
            ['name' => 'Desk Lamp', 'price' => 39.00, 'stock_quantity' => 10],
            ['name' => 'Leather Notebook', 'price' => 29.00, 'stock_quantity' => 8],
            ['name' => 'Bluetooth Speaker', 'price' => 59.00, 'stock_quantity' => 6],
            ['name' => 'Coffee Grinder', 'price' => 49.00, 'stock_quantity' => 5],
            ['name' => 'Travel Mug', 'price' => 15.00, 'stock_quantity' => 18],
        )->create();
    }
}
