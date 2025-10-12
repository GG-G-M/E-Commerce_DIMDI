<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\VariantAttribute;


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
    return redirect()->route('admin.products.index');
}

public function edit(Product $product)
{
    return redirect()->route('admin.products.index');
}


public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'is_featured' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    
    // if (!array_key_exists('slug', $validated) || empty($validated['slug'])) {
    //     $validated['slug'] = Str::slug($validated['name']);
    // }

    // ✅ Convert feature/active flags to proper boolean
    $validated['is_featured'] = $request->boolean('is_featured');
    $validated['is_active'] = $request->boolean('is_active');

    // ✅ Ensure all required columns are present
    Product::create([
        'name' => $validated['name'],
        // 'slug' => $validated['slug'],
        'description' => $validated['description'] ?? null,
        'category_id' => $validated['category_id'],
        'is_featured' => $validated['is_featured'],
        'is_active' => $validated['is_active'],
    ]);

    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Product saved successfully!']);
    }

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
}





public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        // 'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ]);

    // $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
    $validated['is_featured'] = $request->boolean('is_featured');
    $validated['is_active'] = $request->boolean('is_active');

    $product->update($validated);

    if ($request->ajax()) {
    return response()->json(['success' => true, 'message' => 'Product saved successfully!']);
}

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