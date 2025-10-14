<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommerceSeeder extends Seeder
{
    public function run()
    {
        // Create Categories for Appliance Store
        $categories = [
            [
                'name' => 'Kitchen Appliances',
                'description' => 'Essential appliances for modern kitchen efficiency'
            ],
            [
                'name' => 'Laundry Appliances',
                'description' => 'Washers, dryers, and laundry care solutions'
            ],
            [
                'name' => 'Climate Control',
                'description' => 'Heating, cooling, and air quality appliances'
            ],
            [
                'name' => 'Home Entertainment',
                'description' => 'Televisions and entertainment systems'
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

        // Create Products for Appliance Store
        $products = [
            // Laundry Appliances
            [
                'name' => 'Twin Tub Washing Machine (AMERICAN HOME)',
                'description' => 'AMERICAN HOME Twin Tub Washing Machine 6KG',
                'price' => 7195.00,
                'sale_price' => 5800.00,
                'stock_quantity' => 10,
                'sku' => 'SKU-GVIWVZBD',
                'image' => 'images/products/1760414009_twin-tub-washing-machine-american-home.jpg',
                'category_id' => 2,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'variants' => []
            ],

            // Climate Control
            [
                'name' => 'Stand Fan (ASAHI)',
                'description' => 'ASAHI Stand Fan 16" Blade Diameter, 3-Speed Settings',
                'price' => 1000.00,
                'sale_price' => 900.00,
                'stock_quantity' => 38,
                'sku' => 'SKU-7BUGJIXC',
                'image' => 'images/products/1760414108_stand-fan-asahi.jpg',
                'category_id' => 3,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Stand Fan',
                        'variant_description' => NULL,
                        'image' => 'images/products/1760414324_stand-fan-asahi-stand-fan.jpg',
                        'stock' => 20,
                        'price' => 1000.00,
                        'sale_price' => 900.00,
                        'sku' => 'STANDF-STA-QBGX'
                    ],
                    [
                        'variant_name' => 'Floor Fan',
                        'variant_description' => NULL,
                        'image' => 'images/products/1760414324_stand-fan-asahi-floor-fan.jpg',
                        'stock' => 18,
                        'price' => 1000.00,
                        'sale_price' => 800.00,
                        'sku' => 'STANDF-FLO-OE12'
                    ]
                ]
            ],
            [
                'name' => 'Wall Fan 26" (ASAHI)',
                'description' => 'ASAHI WF-2601 26" Industrial Wall Fan, Aluminum Blades',
                'price' => 2000.00,
                'sale_price' => 1900.00,
                'stock_quantity' => 8,
                'sku' => 'SKU-UZRBEZG3',
                'image' => 'images/products/1760414227_wall-fan-26-asahi.jpg',
                'category_id' => 3,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Wall Fan 16" (CAMEL)',
                'description' => 'CAMEL Wall Fan 16" 60W WRF 1603 C',
                'price' => 1630.00,
                'sale_price' => 1280.00,
                'stock_quantity' => 20,
                'sku' => 'SKU-VJHG4ZNQ',
                'image' => 'images/products/1760414403_wall-fan-16-camel.jpg',
                'category_id' => 3,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'variants' => []
            ],

            // Kitchen Appliances
            [
                'name' => 'Coffee Maker (TECHNIK)',
                'description' => 'TECHNIK Coffee Maker TCM-12TN',
                'price' => 1295.00,
                'sale_price' => null,
                'stock_quantity' => 30,
                'sku' => 'SKU-C1A8AH8W',
                'image' => 'images/products/1760414460_coffee-maker-technik.jpg',
                'category_id' => 1,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Coffee Maker (ASAHI)',
                'description' => 'ASAHI Coffee Maker CM-026 Dript',
                'price' => 1015.00,
                'sale_price' => null,
                'stock_quantity' => 30,
                'sku' => 'SKU-25BXSXF6',
                'image' => 'images/products/1760414509_coffee-maker-asahi.jpg',
                'category_id' => 1,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Coffee Maker (IMARFLEX)',
                'description' => 'IMARFLEX Coffee Maker',
                'price' => 1500.00,
                'sale_price' => null,
                'stock_quantity' => 17,
                'sku' => 'SKU-JV3XLI95',
                'image' => 'images/products/1760414733_coffee-maker-imarflex.jpg',
                'category_id' => 1,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'ICM 512AS',
                        'variant_description' => NULL,
                        'image' => 'images/products/1760414733_coffee-maker-imarflex-icm-512as.jpg',
                        'stock' => 10,
                        'price' => 2100.00,
                        'sale_price' => null,
                        'sku' => 'COFFEE-ICM-NZCD'
                    ],
                    [
                        'variant_name' => 'ICM 700S',
                        'variant_description' => NULL,
                        'image' => 'images/products/1760414733_coffee-maker-imarflex-icm-700s.jpg',
                        'stock' => 7,
                        'price' => 1880.00,
                        'sale_price' => null,
                        'sku' => 'COFFEE-ICM-1EDJ'
                    ]
                ]
            ],
            [
                'name' => 'Cordless Electric Kettle',
                'description' => 'Cordless Electric Kettle EK-178',
                'price' => 1450.00,
                'sale_price' => null,
                'stock_quantity' => 30,
                'sku' => 'SKU-ZP4IY7HJ',
                'image' => 'images/products/1760414774_cordless-electric-kettle.jpg',
                'category_id' => 1,
                'is_featured' => false,
                'is_active' => true,
                'has_variants' => false,
                'variants' => []
            ],

            // Home Entertainment
            [
                'name' => 'Quantum UHD TV (DEVANT)',
                'description' => 'DEVANT QUANTUM UHD TV w/ Free Gift Sound Bar SB050',
                'price' => 30000.00,
                'sale_price' => null,
                'stock_quantity' => 16,
                'sku' => 'SKU-VEJEECWB',
                'image' => 'images/products/1760415027_quantum-uhd-tv-devant.jpg',
                'category_id' => 4,
                'is_featured' => true,
                'is_active' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => '50" Inch',
                        'variant_description' => 'Quantum UHD TV50QUHV05 50"',
                        'image' => 'images/products/1760415027_quantum-uhd-tv-devant-50-inch.jpg',
                        'stock' => 5,
                        'price' => 27950.00,
                        'sale_price' => 25150.00,
                        'sku' => 'QUANTU-50I-9CSY'
                    ],
                    [
                        'variant_name' => '65" Inch',
                        'variant_description' => 'Quantum UHD TV65QUHV05 65"',
                        'image' => 'images/products/1760415027_quantum-uhd-tv-devant-65-inch.jpg',
                        'stock' => 8,
                        'price' => 44450.00,
                        'sale_price' => 44000.00,
                        'sku' => 'QUANTU-65I-7USK'
                    ],
                    [
                        'variant_name' => '55" Inch',
                        'variant_description' => 'Quantum UHD TV55QUHV05 55"',
                        'image' => 'images/products/1760415027_quantum-uhd-tv-devant-55-inch.jpg',
                        'stock' => 3,
                        'price' => 30950.00,
                        'sale_price' => 27850.00,
                        'sku' => 'QUANTU-55I-0JMM'
                    ]
                ]
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'stock_quantity' => $productData['stock_quantity'],
                'sku' => $productData['sku'],
                'image' => $productData['image'],
                'is_featured' => $productData['is_featured'],
                'is_active' => $productData['is_active'],
                'is_archived' => false,
                'category_id' => $productData['category_id']
            ]);

            // Create product variants if the product has variants
            if ($productData['has_variants'] && !empty($productData['variants'])) {
                foreach ($productData['variants'] as $variantData) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_name' => $variantData['variant_name'],
                        'variant_description' => $variantData['variant_description'],
                        'image' => $variantData['image'],
                        'sku' => $variantData['sku'],
                        'stock_quantity' => $variantData['stock'],
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'],
                    ]);
                }
                
                // Update product stock to sum of variants
                $product->updateTotalStock();
            }
        }
        
        $this->command->info('Appliances and Variants Created Successfully!'); 
    }
}