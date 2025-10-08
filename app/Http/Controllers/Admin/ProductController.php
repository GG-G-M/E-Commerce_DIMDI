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
        
        $products = Product::with('category')
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
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'One Size'];
        return view('admin.products.create', compact('categories', 'sizes'));
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
            'sizes' => 'required|array',
            'sizes.*' => 'string|max:50',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle main image upload - Store in public directory
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            
            // Create products directory if it doesn't exist
            $directory = public_path('images/products');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move image to public directory
            $image->move($directory, $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'image' => $imagePath,
            'sizes' => json_encode($request->sizes),
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'is_archived' => false,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'One Size'];
        $selectedSizes = $product->available_sizes;
        
        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'selectedSizes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'selected_sizes' => 'required|array|min:1',
            'selected_sizes.*' => 'string',
            'stock' => 'required|array',
            'stock.*' => 'required|integer|min:0',
            'size_price' => 'nullable|array',
            'size_price.*' => 'nullable|numeric|min:0',
            'size_sale_price' => 'nullable|array',
            'size_sale_price.*' => 'nullable|numeric|min:0',
        ], [
            'selected_sizes.required' => 'Please select at least one size.',
            'stock.*.required' => 'Stock quantity is required for all selected sizes.',
            'stock.*.min' => 'Stock quantity cannot be negative.',
        ]);

        // Custom validation for sale price being lower than price
        if ($validated['sale_price'] && $validated['sale_price'] >= $validated['price']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sale_price' => 'Sale price must be lower than regular price.']);
        }

        // Validate size-specific prices
        foreach ($validated['selected_sizes'] as $size) {
            $sizePrice = $validated['size_price'][$size] ?? null;
            $sizeSalePrice = $validated['size_sale_price'][$size] ?? null;
            
            if ($sizeSalePrice && $sizePrice && $sizeSalePrice >= $sizePrice) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(["size_sale_price.{$size}" => "Sale price for {$size} must be lower than regular price."]);
            }
            
            if ($sizeSalePrice && !$sizePrice) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(["size_price.{$size}" => "Regular price is required for {$size} if sale price is set."]);
            }
        }

        // Update basic product info
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'],
            'category_id' => $validated['category_id'],
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sizes' => $validated['selected_sizes'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Your existing image upload logic
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

        // Update or create variants for each selected size
        foreach ($validated['selected_sizes'] as $size) {
            $variantData = [
                'stock_quantity' => $validated['stock'][$size] ?? 0,
                'price' => $validated['size_price'][$size] ?? $validated['price'], // Use base price if not set
            ];

            // Only set sale price if provided, otherwise use base sale price or null
            if (isset($validated['size_sale_price'][$size])) {
                $variantData['sale_price'] = $validated['size_sale_price'][$size];
            } else {
                $variantData['sale_price'] = $validated['sale_price'];
            }

            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'size' => $size,
                ],
                $variantData
            );
        }

        // Remove variants for sizes that are no longer selected
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('size', $validated['selected_sizes'])
            ->delete();

        // Update total stock for backward compatibility
        $product->updateTotalStock();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
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