<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Latest electronic gadgets and devices'
            ],
            [
                'name' => 'Fashion',
                'description' => 'Trendy clothing and accessories'
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Home appliances and kitchenware'
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment and accessories'
            ],
            [
                'name' => 'Books',
                'description' => 'Books and educational materials'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => 'https://via.placeholder.com/300x200',
                'is_active' => true
            ]);
        }

        // Create Products
        $products = [
            // Electronics
            [
                'name' => 'Smartphone X Pro',
                'description' => 'Latest smartphone with advanced features and high-resolution camera.',
                'price' => 899.99,
                'sale_price' => 799.99,
                'stock_quantity' => 50,
                'sku' => 'ELEC-SMP-XPRO-001',
                'category_id' => 1,
                'is_featured' => true
            ],
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium wireless headphones with noise cancellation.',
                'price' => 199.99,
                'sale_price' => 149.99,
                'stock_quantity' => 100,
                'sku' => 'ELEC-WBH-002',
                'category_id' => 1,
                'is_featured' => true
            ],
            [
                'name' => 'Smart Watch Series 5',
                'description' => 'Feature-rich smartwatch with health monitoring.',
                'price' => 299.99,
                'sale_price' => null,
                'stock_quantity' => 75,
                'sku' => 'ELEC-SWS5-003',
                'category_id' => 1,
                'is_featured' => false
            ],
            [
                'name' => 'Laptop Ultra Pro',
                'description' => 'High-performance laptop for professionals and gamers.',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'stock_quantity' => 25,
                'sku' => 'ELEC-LUP-004',
                'category_id' => 1,
                'is_featured' => true
            ],

            // Fashion
            [
                'name' => 'Designer T-Shirt',
                'description' => 'Comfortable cotton t-shirt with modern design.',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock_quantity' => 200,
                'sku' => 'FASH-DTS-005',
                'category_id' => 2,
                'is_featured' => false
            ],
            [
                'name' => 'Denim Jacket',
                'description' => 'Classic denim jacket for all seasons.',
                'price' => 79.99,
                'sale_price' => null,
                'stock_quantity' => 60,
                'sku' => 'FASH-DJ-006',
                'category_id' => 2,
                'is_featured' => true
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Comfortable running shoes with cushion technology.',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock_quantity' => 80,
                'sku' => 'FASH-RS-007',
                'category_id' => 2,
                'is_featured' => false
            ],

            // Home & Kitchen
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker with programmable features.',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock_quantity' => 40,
                'sku' => 'HOME-CM-008',
                'category_id' => 3,
                'is_featured' => true
            ],
            [
                'name' => 'Air Fryer',
                'description' => 'Healthy cooking with less oil using air fryer technology.',
                'price' => 89.99,
                'sale_price' => null,
                'stock_quantity' => 65,
                'sku' => 'HOME-AF-009',
                'category_id' => 3,
                'is_featured' => false
            ],

            // Sports
            [
                'name' => 'Yoga Mat',
                'description' => 'Non-slip yoga mat for comfortable exercise.',
                'price' => 39.99,
                'sale_price' => 29.99,
                'stock_quantity' => 120,
                'sku' => 'SPRT-YM-010',
                'category_id' => 4,
                'is_featured' => false
            ],
            [
                'name' => 'Dumbbell Set',
                'description' => 'Adjustable dumbbell set for home workouts.',
                'price' => 149.99,
                'sale_price' => 129.99,
                'stock_quantity' => 30,
                'sku' => 'SPRT-DS-011',
                'category_id' => 4,
                'is_featured' => true
            ],

            // Books
            [
                'name' => 'Programming Guide',
                'description' => 'Comprehensive guide to modern programming.',
                'price' => 49.99,
                'sale_price' => 39.99,
                'stock_quantity' => 150,
                'sku' => 'BOOK-PG-012',
                'category_id' => 5,
                'is_featured' => false
            ],
            [
                'name' => 'Cookbook Collection',
                'description' => 'Collection of recipes from around the world.',
                'price' => 34.99,
                'sale_price' => null,
                'stock_quantity' => 90,
                'sku' => 'BOOK-CC-013',
                'category_id' => 5,
                'is_featured' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'sale_price' => $product['sale_price'],
                'stock_quantity' => $product['stock_quantity'],
                'sku' => $product['sku'],
                'image' => 'https://via.placeholder.com/400x300',
                'gallery' => json_encode([
                    'https://via.placeholder.com/400x300',
                    'https://via.placeholder.com/400x300',
                    'https://via.placeholder.com/400x300'
                ]),
                'is_featured' => $product['is_featured'],
                'is_active' => true,
                'category_id' => $product['category_id']
            ]);
        }
    }
}