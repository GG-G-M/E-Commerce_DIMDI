<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search');
    $categoryId = $request->get('category_id');
    $status = $request->get('status', 'active');
    
    $products = Product::with(['category', 'variants']) // Add 'variants' here
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
        return view('admin.products.create', compact('categories'));
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
            'brand' => 'nullable|string|max:255',
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
            'brand' => $request->brand,
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
        $variants = $product->variants;
        
        return view('admin.products.edit', compact('product', 'categories', 'variants'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
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
            'brand' => $validated['brand'],
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
}