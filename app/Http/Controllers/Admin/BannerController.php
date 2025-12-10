<?php
// app/Http\Controllers/Admin/BannerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(Request $request) // Add Request parameter
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $status = $request->get('status');
        $sortBy = $request->get('sort_by', 'order');
        $perPage = $request->get('per_page', 10);
        
        // Start query
        $query = Banner::query();
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'order':
            default:
                $query->orderBy('order')->orderBy('created_at', 'desc');
                break;
        }
        
        // Use paginate() instead of get()
        $banners = $query->paginate($perPage);
        
        // Pass request parameters for pagination links
        $banners->appends($request->all());
        
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'target_url' => 'nullable|url|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        try {
            // Create banners directory in public/images/banners (like your products)
            $directory = public_path('images/banners');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Store image like your ProductController does
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->move($directory, $imageName);
            $imagePath = 'images/banners/' . $imageName;

            Banner::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imagePath, // Store full path like products
                'alt_text' => $request->alt_text,
                'target_url' => $request->target_url,
                'order' => $request->order ?? 0,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating banner: ' . $e->getMessage());
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'target_url' => 'nullable|url|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'alt_text' => $request->alt_text,
                'target_url' => $request->target_url,
                'order' => $request->order ?? 0,
                'is_active' => $request->has('is_active')
            ];

            // Update image if new one is uploaded
            if ($request->hasFile('image')) {
                $directory = public_path('images/banners');
                
                // Delete old image if it exists
                if ($banner->image_path && file_exists(public_path($banner->image_path))) {
                    unlink(public_path($banner->image_path));
                }

                // Store new image
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $imageName);
                $data['image_path'] = 'images/banners/' . $imageName;
            }

            $banner->update($data);

            return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating banner: ' . $e->getMessage());
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            // Delete image file from public directory
            if ($banner->image_path && file_exists(public_path($banner->image_path))) {
                unlink(public_path($banner->image_path));
            }

            $banner->delete();

            return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting banner: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Banner $banner)
    {
        try {
            $banner->update([
                'is_active' => !$banner->is_active
            ]);

            $status = $banner->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "Banner {$status} successfully!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating banner status: ' . $e->getMessage());
        }
    }
}