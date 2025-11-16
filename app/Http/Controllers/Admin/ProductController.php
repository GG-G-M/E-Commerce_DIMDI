<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $status = $request->get('status', 'active');
        
        $products = Product::with(['category', 'variants'])
            ->when($search, function($query) use ($search) {
                return $query->search($search);
            })
            ->when($categoryId, function($query) use ($categoryId) {
                return $query->filterByCategory($categoryId);
            })
            ->when($status, function($query) use ($status) {
                return $query->filterByStatus($status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->all());

        $categories = Category::active()->get();
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive', 
            'archived' => 'Archived',
            'featured' => 'Featured',
            'all' => 'All'
        ];

        return view('admin.products.index', compact('products', 'categories', 'statuses'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'has_variants' => 'boolean',
            'variants' => 'required_if:has_variants,1|array',
            'variants.*.variant_name' => 'required_if:has_variants,1|string|max:255',
            'variants.*.variant_description' => 'nullable|string',
            'variants.*.stock' => 'required_if:has_variants,1|integer|min:0',
            'variants.*.price' => 'required_if:has_variants,1|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle main image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            
            $directory = public_path('images/products');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $image->move($directory, $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        // Create product
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'is_archived' => false,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        // Create variants if enabled
        if ($request->has('has_variants') && $request->has_variants && $request->variants) {
            foreach ($request->variants as $variantData) {
                $variantImagePath = null;
                
                // Handle variant image upload
                if (isset($variantData['image']) && $variantData['image']) {
                    $variantImage = $variantData['image'];
                    $variantImageName = time() . '_' . Str::slug($product->name . '-' . $variantData['variant_name']) . '.' . $variantImage->getClientOriginalExtension();
                    
                    $variantImage->move($directory, $variantImageName);
                    $variantImagePath = 'images/products/' . $variantImageName;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variantData['variant_name'],
                    'variant_description' => $variantData['variant_description'] ?? null,
                    'image' => $variantImagePath,
                    'sku' => $product->generateVariantSku($variantData['variant_name']),
                    'stock_quantity' => $variantData['stock'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                ]);
            }
            
            // Update product stock to sum of variants
            $product->updateTotalStock();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $brands = Brand::all();
        $variants = $product->variants;
        
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'variants'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'has_variants' => 'boolean',
            'stock_quantity' => 'required_if:has_variants,0|integer|min:0',
            'variants' => 'required_if:has_variants,1|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.variant_name' => 'required_if:has_variants,1|string|max:255',
            'variants.*.variant_description' => 'nullable|string',
            'variants.*.stock' => 'required_if:has_variants,1|integer|min:0',
            'variants.*.price' => 'required_if:has_variants,1|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'variants.*.remove_image' => 'boolean',
        ]);

        // Update basic product info
        $productData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'],
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ];

        // Handle main image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            
            $directory = public_path('images/products');
            $image->move($directory, $imageName);
            $productData['image'] = 'images/products/' . $imageName;
        }

        $product->update($productData);

        // Handle variants
        if ($request->has('has_variants') && $request->has_variants) {
            $this->updateVariants($product, $request->variants ?? []);
            $product->update(['stock_quantity' => $product->variants->sum('stock_quantity')]);
        } else {
            // No variants, use product stock
            $product->variants()->delete();
            $product->update(['stock_quantity' => $validated['stock_quantity']]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    private function updateVariants(Product $product, array $variantsData)
    {
        $existingVariantIds = [];
        $directory = public_path('images/products');

        foreach ($variantsData as $variantData) {
            $variantImagePath = null;

            // Handle variant image
            if (isset($variantData['image']) && $variantData['image']) {
                $variantImage = $variantData['image'];
                $variantImageName = time() . '_' . Str::slug($product->name . '-' . $variantData['variant_name']) . '.' . $variantImage->getClientOriginalExtension();
                $variantImage->move($directory, $variantImageName);
                $variantImagePath = 'images/products/' . $variantImageName;
            } elseif (isset($variantData['id']) && !isset($variantData['remove_image'])) {
                // Keep existing image if not removing
                $existingVariant = ProductVariant::find($variantData['id']);
                $variantImagePath = $existingVariant->image ?? null;
            }

            $variantDataToSave = [
                'product_id' => $product->id,
                'variant_name' => $variantData['variant_name'],
                'variant_description' => $variantData['variant_description'] ?? null,
                'stock_quantity' => $variantData['stock'],
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'] ?? null,
                'image' => $variantImagePath,
            ];

            // Generate SKU for new variants
            if (!isset($variantData['id'])) {
                $variantDataToSave['sku'] = $product->generateVariantSku($variantData['variant_name']);
            }

            if (isset($variantData['id'])) {
                // Update existing variant
                ProductVariant::where('id', $variantData['id'])->update($variantDataToSave);
                $existingVariantIds[] = $variantData['id'];
            } else {
                // Create new variant
                $newVariant = ProductVariant::create($variantDataToSave);
                $existingVariantIds[] = $newVariant->id;
            }
        }

        // Remove variants that weren't in the submitted data
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $existingVariantIds)
            ->delete();
    }

    public function archive(Product $product)
    {
        $product->archive();
        return redirect()->route('admin.products.index')->with('success', 'Product archived successfully!');
    }

    public function unarchive(Product $product)
    {
        $product->unarchive();
        return redirect()->route('admin.products.index')->with('success', 'Product unarchived successfully!');
    }

    public function destroy(Product $product)
    {
        // Instead of deleting, archive the product
        $product->archive();
        return redirect()->route('admin.products.index')->with('success', 'Product archived successfully!');
    }

    /**
     * Import products from CSV file
     */
    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'duplicate_handling' => 'required|in:skip,update,overwrite',
            'default_status' => 'required|in:active,inactive',
        ]);

        try {
            $file = $request->file('csv_file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0); // Use first row as header
            
            $headers = $csv->getHeader();
            $requiredHeaders = ['name', 'sku', 'price', 'category_id'];
            
            // Validate headers
            foreach ($requiredHeaders as $required) {
                if (!in_array($required, $headers)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Missing required column: {$required}"
                    ], 422);
                }
            }
            
            $records = Statement::create()->process($csv);
            $imported = 0;
            $updated = 0;
            $skipped = 0;
            $errors = [];
            
            foreach ($records as $index => $row) {
                try {
                    // Clean the row data
                    $row = array_map('trim', $row);
                    
                    // Validate required fields
                    if (empty($row['name']) || empty($row['sku']) || empty($row['price']) || empty($row['category_id'])) {
                        $errors[] = "Row " . ($index + 2) . ": Missing required fields";
                        continue;
                    }

                    // Check if category exists
                    $category = Category::find($row['category_id']);
                    if (!$category) {
                        $errors[] = "Row " . ($index + 2) . ": Category ID {$row['category_id']} does not exist";
                        continue;
                    }

                    // Check if brand exists if provided
                    if (!empty($row['brand_id'])) {
                        $brand = Brand::find($row['brand_id']);
                        if (!$brand) {
                            $errors[] = "Row " . ($index + 2) . ": Brand ID {$row['brand_id']} does not exist";
                            continue;
                        }
                    }

                    // Check if product exists
                    $existingProduct = Product::where('sku', $row['sku'])->first();
                    
                    $duplicateHandling = $request->input('duplicate_handling');
                    
                    // Handle duplicates based on user selection
                    if ($existingProduct) {
                        if ($duplicateHandling === 'skip') {
                            $skipped++;
                            continue;
                        } elseif ($duplicateHandling === 'overwrite') {
                            // Delete existing product and create new one
                            $existingProduct->delete();
                            $existingProduct = null;
                        }
                    }

                    $productData = [
                        'name' => $row['name'],
                        'sku' => $row['sku'],
                        'slug' => Str::slug($row['name']),
                        'description' => $row['description'] ?? '',
                        'price' => floatval($row['price']),
                        'sale_price' => !empty($row['sale_price']) ? floatval($row['sale_price']) : null,
                        'stock_quantity' => !empty($row['stock_quantity']) ? intval($row['stock_quantity']) : 0,
                        'category_id' => intval($row['category_id']),
                        'brand_id' => !empty($row['brand_id']) ? intval($row['brand_id']) : null,
                        'image' => $row['image_url'] ?? null,
                        'is_active' => $request->input('default_status') === 'active',
                        'is_featured' => !empty($row['is_featured']) ? filter_var($row['is_featured'], FILTER_VALIDATE_BOOLEAN) : false,
                        'is_archived' => false,
                    ];

                    if ($existingProduct && $duplicateHandling === 'update') {
                        // Update existing product
                        $existingProduct->update($productData);
                        $updated++;
                    } else {
                        // Create new product
                        Product::create($productData);
                        $imported++;
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            $message = "CSV import completed. Imported: {$imported}, Updated: {$updated}, Skipped: {$skipped}";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " and " . (count($errors) - 5) . " more errors";
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'total_errors' => count($errors),
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process CSV: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download CSV template for product import
     */
    public function downloadCSVTemplate(): StreamedResponse
    {
        $fileName = 'product-import-template.csv';
        
        return response()->streamDownload(function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to handle special characters
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers with descriptions
            $headers = [
                'name' => 'Product Name (Required)',
                'sku' => 'SKU - Must be unique (Required)', 
                'price' => 'Price in PHP (Required)',
                'category_id' => 'Category ID from Categories table (Required)',
                'brand_id' => 'Brand ID from Brands table (Optional)',
                'stock_quantity' => 'Initial Stock Quantity (Optional, default: 0)',
                'description' => 'Product Description (Optional)',
                'image_url' => 'Product Image URL (Optional)',
                'sale_price' => 'Sale/Discounted Price (Optional)',
                'is_featured' => 'Featured Product true/false (Optional)'
            ];
            
            fputcsv($file, array_keys($headers));
            fputcsv($file, array_values($headers));
            
            // Empty row for separation
            fputcsv($file, []);
            
            // Example rows
            $examples = [
                [
                    'Sample Product 1',
                    'SKU001',
                    '29.99',
                    '1', // Must be existing category ID
                    '1', // Must be existing brand ID
                    '100',
                    'This is a sample product description',
                    'https://example.com/image1.jpg',
                    '24.99',
                    'true'
                ],
                [
                    'Sample Product 2',
                    'SKU002',
                    '49.99',
                    '2',
                    '', // No brand
                    '50',
                    'Another sample product',
                    '',
                    '', // No sale price
                    'false'
                ]
            ];
            
            foreach ($examples as $example) {
                fputcsv($file, $example);
            }
            
            fclose($file);
        }, $fileName);
    }
}