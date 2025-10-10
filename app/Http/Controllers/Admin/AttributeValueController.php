<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeValueController extends Controller
{
    // Show all values for a specific attribute
    public function index(Attribute $attribute)
    {
        return response()->json($attribute->values); // returns all values as JSON
    }

    // Store a new value
    public function store(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:255'
        ]);

        $value = $attribute->values()->create([
            'value' => $request->value
        ]);

        return response()->json($value);
    }

    // Delete a value
    public function destroy(AttributeValue $value)
    {
        $value->delete();
        return response()->json(['success' => true]);
    }
}
