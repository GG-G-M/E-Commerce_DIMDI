<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of About entries with search and archive filter
     */
    public function index(Request $request)
    {
        $query = About::query();

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // Filter active or archived
        if ($request->status === 'archived') {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        $perPage = $request->get('per_page', 10);
        $abouts = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage)
                        ->appends($request->query());

        return view('admin.abouts.index', compact('abouts'));
    }

    /**
     * Store newly created About section
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'                 => 'required|string|max:255',
            'description_1'         => 'required|string',
            'description_2'         => 'nullable|string',
            'feature_1_title'       => 'required|string|max:255',
            'feature_1_description' => 'required|string',
            'feature_2_title'       => 'required|string|max:255',
            'feature_2_description' => 'required|string',
            'image'                 => 'nullable|string', // If URL or file path
        ]);

        $about = About::create([
            'title'                 => $request->title,
            'description_1'         => $request->description_1,
            'description_2'         => $request->description_2,
            'feature_1_title'       => $request->feature_1_title,
            'feature_1_description' => $request->feature_1_description,
            'feature_2_title'       => $request->feature_2_title,
            'feature_2_description' => $request->feature_2_description,
            'image'                 => $request->image,
            'is_archived'           => false,
        ]);

        return response()->json([
            'success' => true,
            'about'   => $about,
        ]);
    }

    /**
     * Update the specified About section
     */
    public function update(Request $request, $id)
    {
        $about = About::findOrFail($id);

        $request->validate([
            'title'                 => 'required|string|max:255',
            'description_1'         => 'required|string',
            'description_2'         => 'nullable|string',
            'feature_1_title'       => 'required|string|max:255',
            'feature_1_description' => 'required|string',
            'feature_2_title'       => 'required|string|max:255',
            'feature_2_description' => 'required|string',
            'image'                 => 'nullable|string',
        ]);

        $about->update([
            'title'                 => $request->title,
            'description_1'         => $request->description_1,
            'description_2'         => $request->description_2,
            'feature_1_title'       => $request->feature_1_title,
            'feature_1_description' => $request->feature_1_description,
            'feature_2_title'       => $request->feature_2_title,
            'feature_2_description' => $request->feature_2_description,
            'image'                 => $request->image,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Archive About entry
     */
    public function archive($id)
    {
        $about = About::findOrFail($id);
        $about->update(['is_archived' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Unarchive About entry
     */
    public function unarchive($id)
    {
        $about = About::findOrFail($id);
        $about->update(['is_archived' => false]);

        return response()->json(['success' => true]);
    }

    /**
     * Permanently delete About entry
     */
    public function destroy($id)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return response()->json(['success' => true]);
    }
}
