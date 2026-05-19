<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class Boards extends Controller // Renamed from Boards to BoardsController
{
    /**
     * Display a listing of boards.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view boards')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }

        if ($request->ajax()) {
            return $this->getBoardsData($request);
        }

        return view('admin.boards.index');
    }

    /**
     * Get boards data for DataTables.
     */
    private function getBoardsData(Request $request)
    {
        try {
            $query = Board::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', function ($row) use ($request) {
                    static $i = 0;
                    $start = $request->input('start', 0);
                    return $start + (++$i);
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="btn-group btn-group-sm">';

                    // View button
                    $actionBtn .= '<a href="' . route('boards.edit', $row->id) . '" 
                                    class="btn btn-primary" title="Edit">
                                    <i class="fa fa-edit"></i>
                                  </a>';

                    // Delete button
                    $actionBtn .= '<button type="button" 
                                    class="btn btn-danger delete-btn" 
                                    data-id="' . $row->id . '" 
                                    data-name="' . $row->name . '"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                  </button>';

                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->addColumn('status_badge', function ($row) {
                    $badgeClass = $row->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
                    $statusText = $row->status === 'active' ? 'Active' : 'Inactive';
                    return '<span class="' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->addColumn('image_preview', function ($row) {
                    $originalImage = $row->getRawOriginal('image_url');
                    if ($originalImage) {
                        $imageUrl = asset('storage/app/public/board/' . $originalImage);
                        return '<img src="' . $imageUrl . '" 
                                alt="' . $row->name . '" 
                                class="img-thumbnail" 
                                style="width: 60px; height: 60px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">No Image</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : 'N/A';
                })
                ->addColumn('updated_at_formatted', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d-m-Y H:i') : 'N/A';
                })
                ->rawColumns(['action', 'status_badge', 'image_preview'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new board.
     */
    public function create()
    {
        if (!auth()->user()->can('create boards')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        return view('admin.boards.form', [
            'board' => null,
            'page_type' => 'create'
        ]);
    }

    /**
     * Store a newly created board in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create boards')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            DB::beginTransaction();

            $validated = $this->validateRequest($request);
            $validated['slug'] = Str::slug($validated['name']);

            // Handle image upload
            if ($request->hasFile('image_url')) {
                $validated['image_url'] = $this->storeImage($request->file('image_url'));
            }

            Board::create($validated);

            DB::commit();

            return redirect()
                ->route('boards.index')
                ->with('success', 'Board created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified board.
     */
    public function edit(Board $board)
    {
        if (!auth()->user()->can('edit boards')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        return view('admin.boards.form', [
            'board' => $board,
            'page_type' => 'edit'
        ]);
    }

    /**
     * Show the specified board.
     */
    public function show(Board $board)
    {
        return view('admin.boards.show', compact('board'));
    }

    /**
     * Update the specified board in storage.
     */
    public function update(Request $request, $id) // Changed to accept $id to match route
    {
        if (!auth()->user()->can('edit boards')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            DB::beginTransaction();

            $board = Board::findOrFail($id);
            $validated = $this->validateRequest($request, $board->id);

            // Handle image upload or removal
            if ($request->hasFile('image_url')) {
                // Delete old image if exists
                if ($board->getRawOriginal('image_url')) {
                    $this->deleteImage($board->getRawOriginal('image_url'));
                }
                $validated['image_url'] = $this->storeImage($request->file('image_url'));
            } elseif ($request->input('remove_image') == '1') {
                // Remove image if requested
                if ($board->getRawOriginal('image_url')) {
                    $this->deleteImage($board->getRawOriginal('image_url'));
                }
                $validated['image_url'] = null;
            } else {
                // Keep existing image
                $validated['image_url'] = $board->getRawOriginal('image_url');
            }

            // Update slug if name changed
            if ($board->name !== $validated['name']) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $board->update($validated);

            DB::commit();

            return redirect()
                ->route('boards.index')
                ->with('success', 'Board updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified board from storage.
     */
    public function destroy(Board $board)
    {
        if (!auth()->user()->can('delete boards')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            DB::beginTransaction();

            // Delete image if exists
            if ($board->getRawOriginal('image_url')) {
                $this->deleteImage($board->getRawOriginal('image_url'));
            }

            $board->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Board deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle board status.
     */
    public function toggleStatus(Board $board) // Added missing method
    {
        try {
            $board->status = $board->status === 'active' ? 'inactive' : 'active';
            $board->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $board->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation rules for board.
     */
    private function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'content' => 'nullable|string',
        ];

        if (!$id) {
            $rules['image_url'] = 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048';
            $rules['name'] = 'required|string|max:255|unique:boards,name';
        } else {
            $rules['image_url'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048';
            $rules['name'] = 'required|string|max:255|unique:boards,name,' . $id;
        }

        return $request->validate($rules);
    }

    /**
     * Store image to storage.
     */
    private function storeImage($image)
    {
        $ext = $image->getClientOriginalExtension();
        $newName = time() . '-' . Str::random(10) . '.' . $ext;
        $image->storeAs('board', $newName, 'public');
        return $newName;
    }

    /**
     * Delete image from storage.
     */
    private function deleteImage($imageName)
    {
        if (!$imageName) return;

        $imagePath = 'board/' . $imageName;
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
