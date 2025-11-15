<?php
// app/Http/Controllers/Admin/BannerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
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
            // Create banners directory if it doesn't exist
            if (!Storage::exists('public/banners')) {
                Storage::makeDirectory('public/banners');
            }

            // Store image
            $imagePath = $request->file('image')->store('public/banners');
            $imageName = basename($imagePath);

            Banner::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imageName,
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
                // Delete old image
                if (Storage::exists('public/banners/' . $banner->image_path)) {
                    Storage::delete('public/banners/' . $banner->image_path);
                }

                // Store new image
                $imagePath = $request->file('image')->store('public/banners');
                $data['image_path'] = basename($imagePath);
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
            // Delete image file
            if (Storage::exists('public/banners/' . $banner->image_path)) {
                Storage::delete('public/banners/' . $banner->image_path);
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