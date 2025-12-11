<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(8)->sequence(
            [
                'name' => 'Canvas Tote Bag',
                'price' => 24.00,
                'stock_quantity' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Wireless Earbuds',
                'price' => 79.00,
                'stock_quantity' => 12,
                'image_url' => 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Insulated Water Bottle',
                'price' => 19.00,
                'stock_quantity' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1577803645773-f96470509666?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Desk Lamp',
                'price' => 39.00,
                'stock_quantity' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Leather Notebook',
                'price' => 29.00,
                'stock_quantity' => 8,
                'image_url' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Bluetooth Speaker',
                'price' => 59.00,
                'stock_quantity' => 6,
                'image_url' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Coffee Grinder',
                'price' => 49.00,
                'stock_quantity' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1477764227684-8c4e5bca06ea?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Travel Mug',
                'price' => 15.00,
                'stock_quantity' => 18,
                'image_url' => 'https://images.unsplash.com/photo-1517685352821-92cf88aee5a5?auto=format&fit=crop&w=600&q=80',
            ],
        )->create();
    }
}
