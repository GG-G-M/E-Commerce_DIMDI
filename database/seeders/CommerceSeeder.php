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
                'name' => 'Cleaning Appliances',
                'description' => 'Vacuum cleaners and home cleaning solutions'
            ],
            [
                'name' => 'Small Appliances',
                'description' => 'Countertop and portable appliances'
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
            // Kitchen Appliances
            [
                'name' => 'Stainless Steel Refrigerator',
                'description' => 'Energy-efficient French door refrigerator with water dispenser and smart temperature control.',
                'price' => 1899.99,
                'sale_price' => 1699.99,
                'stock_quantity' => 8,
                'sku' => 'KA-FRIDGE-001',
                'category_id' => 1,
                'is_featured' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Standard Edition',
                        'variant_description' => 'Basic model with essential features',
                        'stock' => 5,
                        'price' => 1899.99,
                        'sale_price' => 1699.99,
                    ],
                    [
                        'variant_name' => 'Smart Edition',
                        'variant_description' => 'WiFi enabled with smart home integration',
                        'stock' => 3,
                        'price' => 2299.99,
                        'sale_price' => 1999.99,
                    ]
                ]
            ],
            [
                'name' => 'Professional Stand Mixer',
                'description' => 'Powerful stand mixer with multiple attachments for baking and food preparation.',
                'price' => 349.99,
                'sale_price' => 299.99,
                'stock_quantity' => 25,
                'sku' => 'KA-MIXER-002',
                'category_id' => 1,
                'is_featured' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Classic Model',
                        'variant_description' => '5-quart capacity with 3 attachments',
                        'stock' => 15,
                        'price' => 349.99,
                        'sale_price' => 299.99,
                    ],
                    [
                        'variant_name' => 'Pro Model',
                        'variant_description' => '7-quart capacity with 6 attachments',
                        'stock' => 10,
                        'price' => 499.99,
                        'sale_price' => 449.99,
                    ]
                ]
            ],
            [
                'name' => 'Air Fryer Oven',
                'description' => 'Multi-functional air fryer oven with digital controls and multiple cooking presets.',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock_quantity' => 40,
                'sku' => 'KA-AIRFRY-003',
                'category_id' => 1,
                'is_featured' => false,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Compact Model',
                        'variant_description' => 'Perfect for small kitchens',
                        'stock' => 25,
                        'price' => 99.99,
                        'sale_price' => 79.99,
                    ],
                    [
                        'variant_name' => 'Family Model',
                        'variant_description' => 'Larger capacity for families',
                        'stock' => 15,
                        'price' => 129.99,
                        'sale_price' => 99.99,
                    ]
                ]
            ],
            [
                'name' => 'Coffee Maker Machine',
                'description' => 'Programmable coffee maker with thermal carafe and built-in grinder for fresh coffee.',
                'price' => 199.99,
                'sale_price' => null,
                'stock_quantity' => 30,
                'sku' => 'KA-COFFEE-004',
                'category_id' => 1,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],

            // Laundry Appliances
            [
                'name' => 'Front Load Washer',
                'description' => 'Energy-efficient front load washing machine with multiple wash cycles and steam function.',
                'price' => 899.99,
                'sale_price' => 799.99,
                'stock_quantity' => 12,
                'sku' => 'LA-WASHER-005',
                'category_id' => 2,
                'is_featured' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Standard Capacity',
                        'variant_description' => '4.5 cu.ft capacity',
                        'stock' => 8,
                        'price' => 899.99,
                        'sale_price' => 799.99,
                    ],
                    [
                        'variant_name' => 'Large Capacity',
                        'variant_description' => '5.2 cu.ft capacity with steam',
                        'stock' => 4,
                        'price' => 1199.99,
                        'sale_price' => 999.99,
                    ]
                ]
            ],
            [
                'name' => 'Electric Dryer',
                'description' => 'Electric dryer with moisture sensor and multiple drying cycles.',
                'price' => 699.99,
                'sale_price' => 649.99,
                'stock_quantity' => 15,
                'sku' => 'LA-DRYER-006',
                'category_id' => 2,
                'is_featured' => true,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Washer Dryer Combo',
                'description' => 'All-in-one washer and dryer unit perfect for small spaces.',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'stock_quantity' => 8,
                'sku' => 'LA-COMBO-007',
                'category_id' => 2,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],

            // Climate Control
            [
                'name' => 'Smart Air Conditioner',
                'description' => 'WiFi-enabled air conditioner with smart temperature control and energy monitoring.',
                'price' => 499.99,
                'sale_price' => 449.99,
                'stock_quantity' => 20,
                'sku' => 'CC-AC-008',
                'category_id' => 3,
                'is_featured' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => '8000 BTU',
                        'variant_description' => 'Perfect for small rooms up to 350 sq.ft',
                        'stock' => 12,
                        'price' => 499.99,
                        'sale_price' => 449.99,
                    ],
                    [
                        'variant_name' => '12000 BTU',
                        'variant_description' => 'Ideal for medium rooms up to 550 sq.ft',
                        'stock' => 8,
                        'price' => 699.99,
                        'sale_price' => 599.99,
                    ]
                ]
            ],
            [
                'name' => 'HEPA Air Purifier',
                'description' => 'HEPA air purifier with 3-stage filtration system for clean and fresh indoor air.',
                'price' => 179.99,
                'sale_price' => 149.99,
                'stock_quantity' => 35,
                'sku' => 'CC-PURIFIER-009',
                'category_id' => 3,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Space Heater',
                'description' => 'Energy-efficient ceramic space heater with thermostat and safety features.',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock_quantity' => 50,
                'sku' => 'CC-HEATER-010',
                'category_id' => 3,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],

            // Cleaning Appliances
            [
                'name' => 'Robot Vacuum Cleaner',
                'description' => 'Smart robot vacuum with mapping technology and app control for automated cleaning.',
                'price' => 299.99,
                'sale_price' => 249.99,
                'stock_quantity' => 20,
                'sku' => 'CL-VACUUM-011',
                'category_id' => 4,
                'is_featured' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Basic Model',
                        'variant_description' => 'Standard cleaning with basic navigation',
                        'stock' => 12,
                        'price' => 199.99,
                        'sale_price' => 179.99,
                    ],
                    [
                        'variant_name' => 'Pro Model',
                        'variant_description' => 'Advanced mapping and self-emptying base',
                        'stock' => 8,
                        'price' => 399.99,
                        'sale_price' => 349.99,
                    ]
                ]
            ],
            [
                'name' => 'Upright Vacuum Cleaner',
                'description' => 'Powerful upright vacuum with HEPA filtration and multiple attachments.',
                'price' => 199.99,
                'sale_price' => 179.99,
                'stock_quantity' => 25,
                'sku' => 'CL-UPRIGHT-012',
                'category_id' => 4,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Steam Mop',
                'description' => 'Multi-surface steam mop with adjustable steam settings and washable pads.',
                'price' => 79.99,
                'sale_price' => 59.99,
                'stock_quantity' => 40,
                'sku' => 'CL-MOP-013',
                'category_id' => 4,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],

            // Small Appliances
            [
                'name' => 'Blender Pro',
                'description' => 'High-performance blender with multiple speed settings and durable glass jar.',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock_quantity' => 30,
                'sku' => 'SA-BLENDER-014',
                'category_id' => 5,
                'is_featured' => true,
                'has_variants' => true,
                'variants' => [
                    [
                        'variant_name' => 'Standard Model',
                        'variant_description' => '6-speed settings with 48oz jar',
                        'stock' => 20,
                        'price' => 129.99,
                        'sale_price' => 99.99,
                    ],
                    [
                        'variant_name' => 'Professional Model',
                        'variant_description' => '10-speed settings with 64oz jar',
                        'stock' => 10,
                        'price' => 199.99,
                        'sale_price' => 169.99,
                    ]
                ]
            ],
            [
                'name' => 'Toaster Oven',
                'description' => 'Versatile toaster oven with convection baking and multiple cooking functions.',
                'price' => 89.99,
                'sale_price' => 79.99,
                'stock_quantity' => 35,
                'sku' => 'SA-TOASTER-015',
                'category_id' => 5,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Electric Kettle',
                'description' => 'Fast-boiling electric kettle with temperature control and keep-warm function.',
                'price' => 49.99,
                'sale_price' => 39.99,
                'stock_quantity' => 60,
                'sku' => 'SA-KETTLE-016',
                'category_id' => 5,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
            ],
            [
                'name' => 'Food Processor',
                'description' => 'Multi-functional food processor with multiple blades and attachments.',
                'price' => 149.99,
                'sale_price' => 129.99,
                'stock_quantity' => 20,
                'sku' => 'SA-PROCESSOR-017',
                'category_id' => 5,
                'is_featured' => false,
                'has_variants' => false,
                'variants' => []
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
                'image' => 'https://picsum.photos/400/300?random=' . $productData['sku'],
                'is_featured' => $productData['is_featured'],
                'is_active' => true,
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
                        'sku' => $product->generateVariantSku($variantData['variant_name']),
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