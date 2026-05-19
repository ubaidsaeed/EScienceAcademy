<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class Levels extends Controller
{
    /**
     * Display a listing of levels.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view levels')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getLevelsData($request);
        }
        
        return view('admin.levels.index');
    }
    
    /**
     * Get levels data for DataTables.
     */
    private function getLevelsData(Request $request)
    {
        try {
            $query = Level::with('board');
            
            // Apply filters if any
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }
            
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', function ($row) use ($request) {
                    static $i = 0;
                    $start = $request->input('start', 0);
                    return $start + (++$i);
                })
                ->addColumn('action', function($row) {
                    $actionBtn = '<div class="btn-group btn-group-sm">';
                    
                    // Edit button
                    $actionBtn .= '<a href="'.route('levels.edit', $row->id).'" 
                                    class="btn btn-primary" title="Edit">
                                    <i class="fa fa-edit"></i>
                                  </a>';
                    
                    // Delete button
                    $actionBtn .= '<button type="button" 
                                    class="btn btn-danger delete-btn" 
                                    data-id="'.$row->id.'" 
                                    data-name="'.$row->name.'"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                  </button>';
                    
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->addColumn('status_badge', function($row) {
                    $badgeClass = $row->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
                    $statusText = $row->status === 'active' ? 'Active' : 'Inactive';
                    return '<span class="'.$badgeClass.'">'.$statusText.'</span>';
                })
                ->addColumn('image_preview', function($row) {
                    if ($row->image_url) {
                        $imageUrl = asset('storage/app/public/level/' . $row->image_url);
                        return '<img src="'.$imageUrl.'" 
                                alt="'.$row->name.'" 
                                class="img-thumbnail" 
                                style="width: 60px; height: 60px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">No Image</span>';
                })
                ->addColumn('board_name', function($row) {
                    return $row->board ? $row->board->name : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('created_at_formatted', function($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : 'N/A';
                })
                ->addColumn('updated_at_formatted', function($row) {
                    return $row->updated_at ? $row->updated_at->format('d-m-Y H:i') : 'N/A';
                })
                ->rawColumns(['action', 'status_badge', 'image_preview', 'board_name'])
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
     * Show the form for creating a new level.
     */
    public function create()
    {
        if (!auth()->user()->can('create levels')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $boards = Board::whereNull('parent_id')->get();
        
        return view('admin.levels.form', [
            'page_type' => 'create',
            'boards' => $boards,
            'level' => null,
            'button' => 'Save',
            'form_url' => route('levels.store')
        ]);
    }

    /**
     * Store a newly created level in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        try {
            DB::beginTransaction();
            
            $slug = Str::slug($validated['name']);
            
            // Handle image upload
            if ($request->hasFile('image_url')) {
                $validated['image_url'] = $this->storeImage($request->file('image_url'));
            }
            
            // Handle multiple board IDs
            if (!empty($validated['board_id'])) {
                foreach ($validated['board_id'] as $boardId) {
                    Level::create([
                        'name' => $validated['name'],
                        'board_id' => $boardId,
                        'status' => $validated['status'],
                        'content' => $validated['content'] ?? null,
                        'image_url' => $validated['image_url'] ?? null,
                        'slug' => $slug
                    ]);
                }
            } else {
                Level::create([
                    'name' => $validated['name'],
                    'status' => $validated['status'],
                    'content' => $validated['content'] ?? null,
                    'image_url' => $validated['image_url'] ?? null,
                    'slug' => $slug
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('levels.index')
                ->with('success', 'Level created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified level.
     */
    public function edit(Level $level)
    {
        if (!auth()->user()->can('edit levels')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $boards = Board::whereNull('parent_id')->get();
        
        return view('admin.levels.form', [
            'page_type' => 'edit',
            'boards' => $boards,
            'level' => $level,
            'button' => 'Update',
            'form_url' => route('levels.update', $level->id)
        ]);
    }

    /**
     * Update the specified level in storage.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $this->validateRequest($request, $level->id);
        
        try {
            DB::beginTransaction();
            
            $slug = Str::slug($validated['name']);
            
            // Handle image upload
            if ($request->hasFile('image_url')) {
                // Delete old image if exists
                if ($level->image_url && Storage::exists('level/' . $level->image_url)) {
                    Storage::delete('level/' . $level->image_url);
                }
                $validated['image_url'] = $this->storeImage($request->file('image_url'));
            } elseif ($request->input('remove_image') == '1') {
                // Remove image if requested
                if ($level->image_url && Storage::exists('level/' . $level->image_url)) {
                    Storage::delete('level/' . $level->image_url);
                }
                $validated['image_url'] = null;
            } else {
                // Keep existing image
                $validated['image_url'] = $level->image_url;
            }
            
            // Update level
            $level->update([
                'name' => $validated['name'],
                'board_id' => !empty($validated['board_id']) ? $validated['board_id'][0] : null,
                'status' => $validated['status'],
                'content' => $validated['content'] ?? null,
                'image_url' => $validated['image_url'],
                'slug' => $slug
            ]);
            
            DB::commit();
            
            return redirect()->route('levels.index')
                ->with('success', 'Level updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified level from storage.
     */
    public function destroy(Level $level)
    {
        if (!auth()->user()->can('delete levels')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            DB::beginTransaction();
            
            // Delete image if exists
            if ($level->image_url && Storage::exists('level/' . $level->image_url)) {
                Storage::delete('level/' . $level->image_url);
            }
            
            $level->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Level deleted successfully!'
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
     * Validation rules for level.
     */
    private function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'board_id' => 'nullable|array',
            'board_id.*' => 'exists:boards,id',
            'content' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
        
        if ($id) {
            $rules['name'] = 'required|string|max:255|unique:levels,name,' . $id;
        } else {
            $rules['name'] = 'required|string|max:255|unique:levels,name';
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
        $image->storeAs('level', $newName, 'public');
        return $newName;
    }
    
     public function getLevelsByBoards(Request $request)
    {
        $request->validate([
            'board_ids' => 'required|array',
            'board_ids.*' => 'exists:boards,id'
        ]);

        try {
            $boardIds = $request->board_ids;
            
            // Get levels for the selected boards
            $levels = Level::whereIn('board_id', $boardIds)
                ->where('status', 'active')
                ->with('board')
                ->get()
                ->map(function($level) {
                    return [
                        'id' => $level->id,
                        'name' => $level->name,
                        'board_name' => $level->board->name ?? null
                    ];
                });

            return response()->json([
                'success' => true,
                'levels' => $levels
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load levels: ' . $e->getMessage()
            ], 500);
        }
    }
}