<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'required|array',
            'sizes.*' => 'string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle main image upload
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if it exists in public directory and is not a URL
            if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL) && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
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

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'image' => $imagePath,
            'sizes' => json_encode($request->sizes),
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
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