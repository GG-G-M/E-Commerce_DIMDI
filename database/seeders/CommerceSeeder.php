<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommerceSeeder extends Seeder
{
    public function run()
    {
        // Create Categories for Homegrown Appliance & Furniture Retailer
        $categories = [
            [
                'name' => 'Living Room Furniture',
                'description' => 'Comfortable and stylish furniture for your living space'
            ],
            [
                'name' => 'Bedroom Furniture',
                'description' => 'Beautiful bedroom sets and accessories for restful nights'
            ],
            [
                'name' => 'Kitchen Appliances',
                'description' => 'Essential appliances for modern kitchen efficiency'
            ],
            [
                'name' => 'Home Appliances',
                'description' => 'Appliances to make household tasks easier'
            ],
            [
                'name' => 'Home Decor',
                'description' => 'Decorative items to personalize your space'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true
            ]);
        }
        
        $this->command->info('Categories Created'); 

        // Available sizes
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'One Size'];

        // Create Products for Homegrown Appliance & Furniture Retailer
        $products = [
            // Living Room Furniture
            [
                'name' => 'Modern Leather Sofa',
                'description' => 'Premium genuine leather sofa with comfortable cushioning and modern design. Perfect for contemporary living rooms.',
                'price' => 1299.99,
                'sale_price' => 1099.99,
                'stock_quantity' => 15,
                'sku' => 'LR-SOFA-001',
                'category_id' => 1,
                'is_featured' => true,
                'sizes' => ['L', 'XL']
            ],
            [
                'name' => 'Coffee Table Set',
                'description' => 'Elegant wooden coffee table with matching side tables. Solid oak construction with protective finish.',
                'price' => 299.99,
                'sale_price' => 249.99,
                'stock_quantity' => 25,
                'sku' => 'LR-TABLE-002',
                'category_id' => 1,
                'is_featured' => true,
                'sizes' => ['M', 'L']
            ],
            [
                'name' => 'TV Entertainment Center',
                'description' => 'Spacious entertainment center with adjustable shelves and cable management system. Fits up to 65-inch TVs.',
                'price' => 599.99,
                'sale_price' => null,
                'stock_quantity' => 12,
                'sku' => 'LR-ENT-003',
                'category_id' => 1,
                'is_featured' => false,
                'sizes' => ['XL']
            ],
            [
                'name' => 'Accent Chair',
                'description' => 'Comfortable accent chair with premium fabric and wooden legs. Perfect for reading corners.',
                'price' => 199.99,
                'sale_price' => 179.99,
                'stock_quantity' => 30,
                'sku' => 'LR-CHAIR-004',
                'category_id' => 1,
                'is_featured' => false,
                'sizes' => ['One Size']
            ],

            // Bedroom Furniture
            [
                'name' => 'Queen Size Bed Frame',
                'description' => 'Solid wood queen size bed frame with built-in storage and comfortable headboard.',
                'price' => 899.99,
                'sale_price' => 799.99,
                'stock_quantity' => 18,
                'sku' => 'BR-BED-005',
                'category_id' => 2,
                'is_featured' => true,
                'sizes' => ['Queen']
            ],
            [
                'name' => 'Dresser with Mirror',
                'description' => '6-drawer dresser with attached mirror. Ample storage space with smooth-gliding drawers.',
                'price' => 459.99,
                'sale_price' => null,
                'stock_quantity' => 22,
                'sku' => 'BR-DRESS-006',
                'category_id' => 2,
                'is_featured' => true,
                'sizes' => ['L']
            ],
            [
                'name' => 'Nightstand Set',
                'description' => 'Matching pair of nightstands with drawer storage and built-in USB ports.',
                'price' => 199.99,
                'sale_price' => 169.99,
                'stock_quantity' => 35,
                'sku' => 'BR-NIGHT-007',
                'category_id' => 2,
                'is_featured' => false,
                'sizes' => ['One Size']
            ],
            [
                'name' => 'Wardrobe Cabinet',
                'description' => 'Spacious wardrobe with hanging space, shelves, and mirror. Perfect for bedroom organization.',
                'price' => 699.99,
                'sale_price' => 649.99,
                'stock_quantity' => 10,
                'sku' => 'BR-WARD-008',
                'category_id' => 2,
                'is_featured' => false,
                'sizes' => ['XL']
            ],

            // Kitchen Appliances
            [
                'name' => 'Stainless Steel Refrigerator',
                'description' => 'Energy-efficient French door refrigerator with water dispenser and smart temperature control.',
                'price' => 1899.99,
                'sale_price' => 1699.99,
                'stock_quantity' => 8,
                'sku' => 'KA-FRIDGE-009',
                'category_id' => 3,
                'is_featured' => true,
                'sizes' => ['XL']
            ],
            [
                'name' => 'Professional Stand Mixer',
                'description' => 'Powerful stand mixer with multiple attachments for baking and food preparation.',
                'price' => 349.99,
                'sale_price' => 299.99,
                'stock_quantity' => 25,
                'sku' => 'KA-MIXER-010',
                'category_id' => 3,
                'is_featured' => true,
                'sizes' => ['One Size']
            ],
            [
                'name' => 'Air Fryer Oven',
                'description' => 'Multi-functional air fryer oven with digital controls and multiple cooking presets.',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock_quantity' => 40,
                'sku' => 'KA-AIRFRY-011',
                'category_id' => 3,
                'is_featured' => false,
                'sizes' => ['M']
            ],
            [
                'name' => 'Coffee Maker Machine',
                'description' => 'Programmable coffee maker with thermal carafe and built-in grinder for fresh coffee.',
                'price' => 199.99,
                'sale_price' => null,
                'stock_quantity' => 30,
                'sku' => 'KA-COFFEE-012',
                'category_id' => 3,
                'is_featured' => false,
                'sizes' => ['One Size']
            ],

            // Home Appliances
            [
                'name' => 'Robot Vacuum Cleaner',
                'description' => 'Smart robot vacuum with mapping technology and app control for automated cleaning.',
                'price' => 299.99,
                'sale_price' => 249.99,
                'stock_quantity' => 20,
                'sku' => 'HA-VACUUM-013',
                'category_id' => 4,
                'is_featured' => true,
                'sizes' => ['One Size']
            ],
            [
                'name' => 'Air Purifier',
                'description' => 'HEPA air purifier with 3-stage filtration system for clean and fresh indoor air.',
                'price' => 179.99,
                'sale_price' => 149.99,
                'stock_quantity' => 35,
                'sku' => 'HA-PURIFIER-014',
                'category_id' => 4,
                'is_featured' => false,
                'sizes' => ['M']
            ],
            [
                'name' => 'Garment Steamer',
                'description' => 'Powerful garment steamer with continuous steam output for wrinkle-free clothes.',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock_quantity' => 50,
                'sku' => 'HA-STEAMER-015',
                'category_id' => 4,
                'is_featured' => false,
                'sizes' => ['One Size']
            ],

            // Home Decor
            [
                'name' => 'Decorative Throw Pillows',
                'description' => 'Set of 4 premium decorative throw pillows with various patterns and textures.',
                'price' => 79.99,
                'sale_price' => 59.99,
                'stock_quantity' => 60,
                'sku' => 'HD-PILLOWS-016',
                'category_id' => 5,
                'is_featured' => true,
                'sizes' => ['S', 'M', 'L']
            ],
            [
                'name' => 'Wall Art Canvas Set',
                'description' => 'Set of 3 modern abstract canvas wall art pieces to enhance your home decor.',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock_quantity' => 25,
                'sku' => 'HD-ART-017',
                'category_id' => 5,
                'is_featured' => true,
                'sizes' => ['M', 'L']
            ],
            [
                'name' => 'Area Rug',
                'description' => 'Soft and durable area rug with non-slip backing. Available in multiple sizes.',
                'price' => 199.99,
                'sale_price' => 159.99,
                'stock_quantity' => 18,
                'sku' => 'HD-RUG-018',
                'category_id' => 5,
                'is_featured' => false,
                'sizes' => ['S', 'M', 'L', 'XL']
            ],
            [
                'name' => 'Table Lamp Set',
                'description' => 'Pair of elegant table lamps with fabric shades and touch dimmer controls.',
                'price' => 149.99,
                'sale_price' => null,
                'stock_quantity' => 30,
                'sku' => 'HD-LAMPS-019',
                'category_id' => 5,
                'is_featured' => false,
                'sizes' => ['One Size']
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
                'image' => 'https://picsum.photos/400/300?random=' . $product['sku'],
                'sizes' => json_encode($product['sizes']),
                'is_featured' => $product['is_featured'],
                'is_active' => true,
                'is_archived' => false,
                'category_id' => $product['category_id']
            ]);
        }
        
        $this->command->info('Products Created'); 
    }
}