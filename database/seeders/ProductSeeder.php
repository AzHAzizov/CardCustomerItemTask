<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['id' => 1, 'name' => 'iPhone 15 Pro', 'price' => 29999],
            ['id' => 2, 'name' => 'Samsung Galaxy S24', 'price' => 24999],
            ['id' => 3, 'name' => 'Xiaomi 14', 'price' => 17999],
            ['id' => 4, 'name' => 'MacBook Pro', 'price' => 54999],
            ['id' => 5, 'name' => 'Dell XPS 13', 'price' => 38999],
            ['id' => 6, 'name' => 'Sony WH-1000XM5', 'price' => 8999],
            ['id' => 7, 'name' => 'Apple Watch Series 9', 'price' => 10999],
            ['id' => 8, 'name' => 'iPad Pro', 'price' => 31999],
            ['id' => 9, 'name' => 'Logitech MX Master 3S', 'price' => 2999],
            ['id' => 10, 'name' => 'Kindle Paperwhite', 'price' => 4999],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
