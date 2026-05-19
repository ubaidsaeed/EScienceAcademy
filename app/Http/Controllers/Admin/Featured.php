<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Featured extends Controller
{
    public function index()
    {
    if (!auth()->user()->can('view feature')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $features = Feature::orderBy('sort_order')->get();
        return view('admin.featured.index', compact('features'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create feature')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $request->validate([
            'name' => 'required|string|max:255|unique:features,name',
            'icon' => 'nullable|string|max:255',
        ]);

        $status =0;
        if($request->status)
        {
            $status = 1;
        }else{
            $status =0;
        }
        $feature = Feature::create([
            'name'        => $request->name,
            'key'         => $request->filled('key') ? $request->key : Str::slug($request->name),
            'icon'        => $request->icon,
            'has_content' => $request->boolean('has_content'),
            'status'      => $status,
            'sort_order'  => Feature::max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feature created successfully!',
            'data'
        ]);
    }

    public function destroy(Feature $feature)
    {
        if (!auth()->user()->can('delete feature')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $sortOrder = $feature->sort_order;

        $feature->delete();

        // Decrement sort_order of all higher items
        Feature::where('sort_order', '>', $sortOrder)->decrement('sort_order');

        return response()->json([
            'success' => true,
            'message' => 'Feature deleted successfully!'
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:features,id'
        ]);

        foreach ($request->order as $index => $id) {
            Feature::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!'
        ]);
    }

    public function toggleStatus(Feature $feature)
    {
        $feature->update(['status' => !$feature->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status'  => $feature->fresh()->status
        ]);
    }
}
