<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Espresso',
                'description' => 'A concentrated form of coffee served in small, strong shots.',
                'price' => 2.99,
                'category' => 'Hot Coffee',
                'image_url' => 'https://example.com/espresso.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk foam.',
                'price' => 3.99,
                'category' => 'Hot Coffee',
                'image_url' => 'https://example.com/cappuccino.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Iced Latte',
                'description' => 'Chilled espresso with cold milk and ice.',
                'price' => 4.49,
                'category' => 'Cold Coffee',
                'image_url' => 'https://example.com/iced-latte.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Chocolate Muffin',
                'description' => 'Fresh-baked chocolate chip muffin.',
                'price' => 2.99,
                'category' => 'Pastries',
                'image_url' => 'https://example.com/muffin.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Croissant',
                'description' => 'Buttery, flaky French pastry.',
                'price' => 2.49,
                'category' => 'Pastries',
                'image_url' => 'https://example.com/croissant.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
