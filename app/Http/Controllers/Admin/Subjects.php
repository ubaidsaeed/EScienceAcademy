<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Board;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class Subjects extends Controller
{
    /**
     * Display a listing of subjects.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view subjects')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getSubjectsData($request);
        }
        
        return view('admin.subjects.index');
    }
    
    /**
     * Get subjects data for DataTables.
     */
    private function getSubjectsData(Request $request)
{
    try {
        // Use the pivot table relationship
        $query = Subject::with(['subjectBoards.board', 'subjectBoards.level']);
        
        // Apply filters if any
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Apply search filter
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            
            $query->where(function ($q) use ($search) {
                $q->where('subjects.name', 'like', "%$search%")
                  ->orWhere('subjects.slug', 'like', "%$search%")
                  ->orWhereHas('subjectBoards.board', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  })
                  ->orWhereHas('subjectBoards.level', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }
        
        // Get total records count
        $totalRecords = $query->count();
        
        // Apply ordering
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        
        if ($orderColumn !== null) {
            $columnName = $request->input("columns.{$orderColumn}.name");
            
            switch($columnName) {
                case 'name':
                    $query->orderBy('subjects.name', $orderDir);
                    break;
                case 'status':
                    $query->orderBy('subjects.status', $orderDir);
                    break;
                case 'created_at':
                    $query->orderBy('subjects.created_at', $orderDir);
                    break;
                case 'board_names':
                    // Order by board names
                    $query->leftJoin('subject_boards', 'subjects.id', '=', 'subject_boards.subject_id')
                          ->leftJoin('boards', 'subject_boards.board_id', '=', 'boards.id')
                          ->select('subjects.*')
                          ->groupBy('subjects.id')
                          ->orderByRaw("GROUP_CONCAT(boards.name ORDER BY boards.name) $orderDir");
                    break;
                case 'level_names':
                    // Order by level names
                    $query->leftJoin('subject_boards', 'subjects.id', '=', 'subject_boards.subject_id')
                          ->leftJoin('levels', 'subject_boards.level_id', '=', 'levels.id')
                          ->select('subjects.*')
                          ->groupBy('subjects.id')
                          ->orderByRaw("GROUP_CONCAT(levels.name ORDER BY levels.name) $orderDir");
                    break;
                default:
                    $query->orderBy('subjects.created_at', 'desc');
            }
        } else {
            $query->orderBy('subjects.created_at', 'desc');
        }
        
        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $query->offset($start)->limit($length);
        
        // Get the data
        $subjects = $query->get();
        
        // Format data for DataTables
        $data = [];
        foreach ($subjects as $index => $subject) {
            // Get unique board names
            $boardNames = $subject->subjectBoards
                ->map(function($subjectBoard) {
                    return $subjectBoard->board ? $subjectBoard->board->name : null;
                })
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');
            
            // Get unique level names
            $levelNames = $subject->subjectBoards
                ->map(function($subjectBoard) {
                    return $subjectBoard->level ? $subjectBoard->level->name : null;
                })
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');
            
            // Create row data
            $data[] = [
                'DT_RowIndex' => $start + $index + 1,
                'image_preview' => $this->getImagePreview($subject),
                'name' => $subject->name,
                'board_names' => $boardNames ?: '<span class="text-muted">N/A</span>',
                'level_names' => $levelNames ?: '<span class="text-muted">N/A</span>',
                'status_badge' => $this->getStatusBadge($subject),
                'created_at_formatted' => $subject->created_at ? $subject->created_at->format('d-m-Y H:i') : 'N/A',
                'action' => $this->getActionButtons($subject)
            ];
        }
        
        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error in getSubjectsData: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Error loading data: ' . $e->getMessage()
        ], 500);
    }
}

// Helper methods for cleaner code
private function getImagePreview($subject)
{
    if ($subject->image_url) {
        $imageUrl = asset('storage/app/public/subject/' . $subject->image_url);
        return '<img src="'.$imageUrl.'" 
                alt="'.$subject->name.'" 
                class="img-thumbnail" 
                style="width: 60px; height: 60px; object-fit: cover;">';
    }
    return '<span class="text-muted">No Image</span>';
}

private function getStatusBadge($subject)
{
    $badgeClass = $subject->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
    $statusText = $subject->status === 'active' ? 'Active' : 'Inactive';
    return '<span class="'.$badgeClass.'">'.$statusText.'</span>';
}

private function getActionButtons($subject)
{
    $actionBtn = '<div class="btn-group btn-group-sm">';
    $actionBtn .= '<a href="'.route('subjects.edit', $subject->id).'" 
                    class="btn btn-primary" title="Edit">
                    <i class="fa fa-edit"></i>
                  </a>';
    $actionBtn .= '<button type="button" 
                    class="btn btn-danger delete-btn" 
                    data-id="'.$subject->id.'" 
                    data-name="'.$subject->name.'"
                    title="Delete">
                    <i class="fa fa-trash"></i>
                  </button>';
    $actionBtn .= '</div>';
    return $actionBtn;
}

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        if (!auth()->user()->can('create subjects')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $boards = Board::whereNull('parent_id')->get();
        $levels = Level::all();
        
        return view('admin.subjects.form', [
            'page_type' => 'create',
            'boards' => $boards,
            'levels' => $levels,
            'existingRecords' => collect([]),
            'existingLevels' => collect([]),
            'subject' => null,
            'button' => 'Save',
            'form_url' => route('subjects.store')
        ]);
    }

    /**
     * Store a newly created subject in storage.
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
            
            // Create subject
             Subject::create([
                'name' => $validated['name'],
                'board_id' => null,
                'level_id' =>null,
                'status' => $validated['status'],
                'image_url' => $validated['image_url'] ?? null,
                'slug' => $slug
            ]);
            $id = DB::getPdo()->lastInsertId(); 
            
           DB::table('subject_boards')->where('subject_id', $id)->delete();
        
        // Create new associations from arrays
        if ($request->has('board') && is_array($request->board)) {
            foreach ($request->board as $boardId) {
                // If levels is also an array, create associations for each level
                if ($request->has('level') && is_array($request->level)) {
                    foreach ($request->level as $levelId) {
                        // dd($boardId,$levelId);
                        DB::table('subject_boards')->insert([
                            'subject_id' => $id,
                            'board_id' => $boardId,
                            'level_id' => $levelId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // If only one level or level is not array
                    $levelId = $request->level;
                    DB::table('subject_boards')->insert([
                        'subject_id' => $id,
                        'board_id' => $boardId,
                        'level_id' => $levelId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
            DB::commit();
            
            return redirect()->route('subjects.index')
                ->with('success', 'Subject created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        if (!auth()->user()->can('edit subjects')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $boards = Board::whereNull('parent_id')->get();
        $levels = Level::all();
        $existingRecords = DB::table('subject_boards')
        ->where('subject_id',$subject->id)->get();
         $existingLevels = Level::whereIn('id', $existingRecords->pluck('level_id'))
            ->with('board')
            ->get();
        return view('admin.subjects.form', [
            'page_type' => 'edit',
            'boards' => $boards,
            'subject' => $subject,
            'existingRecords' => $existingRecords,
            'existingLevels' => $existingLevels,
            'button' => 'Update',
            'form_url' => route('subjects.update', $subject->id)
        ]);
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $this->validateRequest($request, $subject->id);
        
        try {
            DB::beginTransaction();
            
            $slug = Str::slug($validated['name']);
            
            // Handle image upload
            if ($request->hasFile('image_url')) {
                // Delete old image if exists
                if ($subject->image_url && Storage::exists('subject/' . $subject->image_url)) {
                    Storage::delete('subject/' . $subject->image_url);
                }
                $validated['image_url'] = $this->storeImage($request->file('image_url'));
            } elseif ($request->input('remove_image') == '1') {
                // Remove image if requested
                if ($subject->image_url && Storage::exists('subject/' . $subject->image_url)) {
                    Storage::delete('subject/' . $subject->image_url);
                }
                $validated['image_url'] = null;
            } else {
                // Keep existing image
                $validated['image_url'] = $subject->image_url;
            }
            
            // Update subject
            $subject->update([
                'name' => $validated['name'],
                'status' => $validated['status'],
                'image_url' => $validated['image_url'],
                'slug' => $slug
            ]);

          DB::table('subject_boards')->where('subject_id', $subject->id)->delete();
        
        // Create new associations from arrays
        if ($request->has('board') && is_array($request->board)) {
            foreach ($request->board as $boardId) {
                // If levels is also an array, create associations for each level
                if ($request->has('level') && is_array($request->level)) {
                    foreach ($request->level as $levelId) {
                        // dd($boardId,$levelId);
                        DB::table('subject_boards')->insert([
                            'subject_id' => $subject->id,
                            'board_id' => $boardId,
                            'level_id' => $levelId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // If only one level or level is not array
                    $levelId = $request->level;
                    DB::table('subject_boards')->insert([
                        'subject_id' => $subject->id,
                        'board_id' => $boardId,
                        'level_id' => $levelId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
            
            DB::commit();
            
            return redirect()->route('subjects.index')
                ->with('success', 'Subject updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        if (!auth()->user()->can('delete subjects')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            DB::beginTransaction();
            
            // Delete image if exists
            if ($subject->image_url && Storage::exists('subject/' . $subject->image_url)) {
                Storage::delete('subject/' . $subject->image_url);
            }
            
            $subject->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully!'
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
     * Validation rules for subject.
     */
    private function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'board' => 'required',
            'level' => 'required',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
        
        if ($id) {
            $rules['name'] = 'required|string|max:255|unique:subjects,name,' . $id;
        } else {
            $rules['name'] = 'required|string|max:255|unique:subjects,name';
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
        $image->storeAs('subject', $newName, 'public');
        return $newName;
    }
     public function getLevelsByBoards(Request $request)
    {
        try {
            // Debug: Log the request
            \Log::info('getLevelsByBoards called', ['request' => $request->all()]);
            
            $boardIds = $request->input('board_ids', []);
            
            if (empty($boardIds)) {
                return response()->json([
                    'success' => true,
                    'levels' => [],
                    'message' => 'Please select at least one board'
                ]);
            }
            
            // Get levels for the selected boards
            $levels = Level::whereIn('board_id', $boardIds)
                ->where('status', 'active')
                ->with('board')
                ->orderBy('name')
                ->get()
                ->map(function($level) {
                    return [
                        'id' => $level->id,
                        'name' => $level->name,
                        'board_name' => $level->board ? $level->board->name : 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'levels' => $levels,
                'count' => $levels->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getLevelsByBoards: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}