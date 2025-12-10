<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $brands = Brand::when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($status === 'archived', function($query) {
                return $query->where('is_archived', true);
            })
            ->when($status === 'active', function($query) {
                return $query->where('is_active', true)->where('is_archived', false);
            })
            ->when($status === 'inactive', function($query) {
                return $query->where('is_active', false)->where('is_archived', false);
            })
            ->when(!$status, function($query) {
                return $query->where('is_archived', false);
            })
            ->ordered()
            ->paginate(10);
            
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'is_archived' => false,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created successfully!');
    }

    public function show(Brand $brand)
    {
        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? $brand->sort_order
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand)
    {
        // Actually archive instead of delete
        $brand->is_archived = true;
        $brand->save();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand archived successfully!');
    }

    public function archive(Brand $brand)
    {
        $brand->is_archived = true;
        $brand->save();

        return response()->json(['success' => true, 'message' => 'Brand archived successfully']);
    }

    public function unarchive(Brand $brand)
    {
        $brand->is_archived = false;
        $brand->save();

        return response()->json(['success' => true, 'message' => 'Brand unarchived successfully']);
    }

    // API endpoint for quick brand creation from product form
    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands'
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => true,
            'is_archived' => false
        ]);

        return response()->json([
            'success' => true,
            'brand' => [
                'id' => $brand->id,
                'name' => $brand->name
            ]
        ]);
    }
}
