<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $categories = Category::when($search, function($query) use ($search) {
                return $query->search($search);
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
            ->latest()
            ->paginate(10)
            ->appends($request->all());

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'is_archived' => false,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Actually archive instead of delete
        $category->is_archived = true;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category archived successfully!');
    }

    public function archive(Category $category)
    {
        $category->is_archived = true;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Category archived successfully']);
    }

    public function unarchive(Category $category)
    {
        $category->is_archived = false;
        $category->save();

        return response()->json(['success' => true, 'message' => 'Category unarchived successfully']);
    }
}
