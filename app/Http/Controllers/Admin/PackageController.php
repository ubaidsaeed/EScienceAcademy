<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Board;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('view packages')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            $packages = SubscriptionPlan::with(['board', 'level'])
                ->select('subscription_plans.*');

            return DataTables::of($packages)
                ->addColumn('board_name', function ($package) {
                    return $package->board ? $package->board->name : 'N/A';
                })
                ->addColumn('level_name', function ($package) {
                    return $package->level ? $package->level->name : 'N/A';
                })
                ->addColumn('features', function ($package) {
                    $features = [];
                    if ($package->online_notes) $features[] = 'Online Notes';
                    if ($package->top_past_paper) $features[] = 'Past Papers';
                    if ($package->ws_aw_bg) $features[] = 'Worksheets';
                    if ($package->recorded) $features[] = 'Recorded Lectures';
                    return implode(', ', $features);
                })
                ->addColumn('total_subjects', function ($package) {
                    return $package->subjects()->count();
                })
                ->addColumn('status_badge', function ($package) {
                    $status = $package->status ? 'active' : 'inactive';
                    $color = $package->status ? 'success' : 'danger';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
                })
                ->addColumn('action', function ($package) {

                    $user = auth()->user();
                
                    $html = '<div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">';
                
                    if ($user->can('edit packages')) {
                        $html .= '
                            <a class="dropdown-item" href="' . route('admin.packages.edit', $package->id) . '">
                                <i class="fas fa-edit"></i> Edit
                            </a>';
                    }
                
                    if ($user->can('create package subject')) {
                        $html .= '
                            <a class="dropdown-item" href="' . route('admin.packages.subjects', $package->id) . '">
                                <i class="fas fa-book"></i> Manage Subjects
                            </a>';
                    }
                
                    if ($user->can('delete packages')) {
                        $html .= '<div class="dropdown-divider"></div>';
                
                        $html .= '
                            <a class="dropdown-item text-danger delete-package" 
                               href="#" data-id="' . $package->id . '">
                                <i class="fas fa-trash"></i> Delete
                            </a>';
                    }
                
                    $html .= '</div></div>';
                
                    return $html;
                })
                ->rawColumns(['status_badge', 'action', 'features'])
                ->make(true);
        }
        // dd('packages');
        return view('admin.packages.index');
    }
    public function create()
    {
         if (!auth()->user()->can('create packages')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $boards = Board::where('status', true)->get();
        $levels = collect(); // Empty initially - will be loaded via AJAX
        $subjects = collect();
        $folderHierarchy = $this->buildTreeHierarchy();
        $assignedContent = [];

        return view('admin.packages.form', compact(
            'boards',
            'levels',
            'subjects',
            'folderHierarchy',
            'assignedContent'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
            'level_id' => 'required|exists:levels,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:days,months,years',
            'student_limit' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'content' => 'nullable',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        $package = SubscriptionPlan::create($validated);
        $package->features()->sync($request->input('features', []));

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully!');
    }

    public function edit($id)
    {
         if (!auth()->user()->can('edit packages')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $package = SubscriptionPlan::with(['subjects'])->findOrFail($id);
        $boards = Board::where('status', true)->get();
        $levels = collect();
        if ($package->board_id) {
            $levels = Level::where('board_id', $package->board_id)
                ->where('status', true)
                ->get();
        }
        
        $subjects = $package->subjects;
        $folderHierarchy = $this->buildTreeHierarchy();
        $availableSubjects = Subject::where('status', 'active')->get();
        // Load assigned content per feature per subject
        $assignedContent = [];
        foreach (Feature::where('has_content', true)->get() as $feature) {
            foreach ($subjects as $subject) {
                $assignedContent[$feature->id][$subject->id] = DB::table('subject_content')
                    ->where('subject_id', $subject->id)
                    ->where('feature_id', $feature->id)
                    ->get()
                    ->map(fn($row) => $row->folder_id ? "folder_{$row->folder_id}" : "file_{$row->media_id}")
                    ->toArray();
            }
        }

        return view('admin.packages.form', compact(
            'package',
            'boards',
            'levels',
            'subjects',
            'availableSubjects',
            'folderHierarchy',
            'assignedContent'
        ));
    }

    public function update(Request $request, $id)
    {
        $package = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
            'level_id' => 'required|exists:levels,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:days,months,years',
            'student_limit' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'features' => 'array',
            'features.*' => 'exists:features,id',
            'content' => 'nullable',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');

        $package->update($validated);
        $package->features()->sync($request->input('features', []));

        // Save content assignments
        $this->saveContentAssignments($package, $request);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully!');
    }

    private function saveContentAssignments($package, $request)
    {
        // If no content data was sent → exit
        if (!$request->has('content') || !is_array($request->content)) {
            return;
        }

        $content = $request->content; // Now it's confirmed to be array

        foreach ($content as $featureId => $subjectsData) {
            // Make sure subjectsData is an array
            if (!is_array($subjectsData)) {
                continue;
            }

            foreach ($subjectsData as $subjectId => $contentIds) {
                // Skip if empty or not string
                if (empty($contentIds) || !is_string($contentIds)) {
                    continue;
                }

                // Clear old assignments
                DB::table('subject_content')
                    ->where('subject_id', $subjectId)
                    ->where('feature_id', $featureId)
                    ->delete();

                $ids = array_filter(explode(',', $contentIds)); // Clean empty values
                $insertData = [];

                foreach ($ids as $id) {
                    $id = trim($id);
                    if (empty($id)) continue;

                    if (str_starts_with($id, 'folder_')) {
                        $insertData[] = [
                            'subject_id' => $subjectId,
                            'feature_id' => $featureId,
                            'folder_id'  => (int) str_replace('folder_', '', $id),
                            'media_id'   => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    } elseif (str_starts_with($id, 'file_')) {
                        $insertData[] = [
                            'subject_id' => $subjectId,
                            'feature_id' => $featureId,
                            'folder_id'  => null,
                            'media_id'   => (int) str_replace('file_', '', $id),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($insertData)) {
                    DB::table('subject_content')->insert($insertData);
                }
            }
        }
    }

    private function buildTreeHierarchy()
    {
        $folders = DB::table('folders')
            ->select('id as folder_id', 'parent_id', 'name as folder_name')
            ->get();

        $files = DB::table('files')
            ->select('id', 'folder_id', 'name', 'original_name', 'extension')
            ->get()
            ->groupBy('folder_id');

        $indexed = [];
        foreach ($folders as $folder) {
            $node = [
                'id' => 'folder_' . $folder->folder_id,
                'text' => $folder->folder_name,
                'icon' => 'fa fa-folder text-warning',
                'children' => [],
                'state' => ['opened' => false]
            ];

            if (isset($files[$folder->folder_id])) {
                foreach ($files[$folder->folder_id] as $file) {
                    $ext = strtolower($file->extension ?? pathinfo($file->original_name, PATHINFO_EXTENSION));
                    $node['children'][] = [
                        'id' => 'file_' . $file->id,
                        'text' => $file->name ?? $file->original_name,
                        'icon' => $this->getFileIcon($ext),
                    ];
                }
            }

            $indexed[$folder->folder_id] = $node;
        }

        $tree = [];
        foreach ($indexed as $id => &$node) {
            $folder = $folders->firstWhere('folder_id', str_replace('folder_', '', $id));
            if ($folder && $folder->parent_id && isset($indexed[$folder->parent_id])) {
                $indexed[$folder->parent_id]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }

        return $tree;
    }


    public function manageSubjects($id)
    {
        if (!auth()->user()->can('create package subject')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $package = SubscriptionPlan::with(['subjects', 'board', 'level'])->findOrFail($id);
            $availableSubjects = DB::table('subjects')
            ->join('subject_boards','subjects.id','=','subject_boards.subject_id')
            ->where('subject_boards.level_id',$package->level_id)
            ->whereNotIn('subject_boards.subject_id', $package->subjects->pluck('id'))
            ->distinct()
            ->select([
                'id' => 'subjects.id',
                'name' =>'subjects.name',
            ])
            ->get();
        return view('admin.packages.manage-subjects', compact('package', 'availableSubjects'));
    }
    public function addSubject(Request $request, $id)
    {
        if (!auth()->user()->can('create package subject')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $package = SubscriptionPlan::findOrFail($id);

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'subject_price' => 'required|numeric|min:0'
        ]);

        // Check if subject already exists in package
        if ($package->subjects()->where('subject_id', $request->subject_id)->exists()) {
            return redirect()->back()->with('error', 'Subject already exists in this package.');
        }

        $package->subjects()->attach($request->subject_id, [
            'subject_price' => $request->subject_price
        ]);

        return redirect()->back()->with('success', 'Subject added to package successfully.');
    }

    public function updateSubjectPrice(Request $request, $packageId, $subjectId)
    {
        if (!auth()->user()->can('edit package subject')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $request->validate([
            'subject_price' => 'required|numeric|min:0'
        ]);

        $package = SubscriptionPlan::findOrFail($packageId);
        $package->subjects()->updateExistingPivot($subjectId, [
            'subject_price' => $request->subject_price
        ]);

        return response()->json(['success' => 'Price updated successfully']);
    }

    public function removeSubject($packageId, $subjectId)
    {
        if (!auth()->user()->can('delete package subject')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $package = SubscriptionPlan::findOrFail($packageId);
        $package->subjects()->detach($subjectId);

        return redirect()->back()->with('success', 'Subject removed from package.');
    }
    public function destroy($id)
    {
        $package = SubscriptionPlan::findOrFail($id);

        // Check if any user is currently subscribed to this package
        $hasActiveSubscriptions = DB::table('subscriptions') // ← Change this table name if different
            ->where('plan_id', $package->id)
            // ->where('status', 'active') // or check end_date > now()
            ->exists();

        if ($hasActiveSubscriptions) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete package. Some students are actively subscribed to it.'
            ], 400);
        }

        // Safe delete everything
        DB::transaction(function () use ($package) {
            // 1. Delete content assignments (subject_content)
            DB::table('subject_content')
                ->join('subscription_plan_subject', 'subject_content.subject_id', '=', 'subscription_plan_subject.subject_id')
                ->where('subscription_plan_subject.subscription_plan_id', $package->id)
                ->delete();

            // 2. Remove subject assignments
            DB::table('subscription_plan_subject')
                ->where('subscription_plan_id', $package->id)
                ->delete();

            // 3. Remove feature assignments
            DB::table('subscription_plan_feature') // or subscription_plan_feature
                ->where('subscription_plan_id', $package->id)
                ->delete();

            // 4. (Optional) Remove any user subscriptions (even inactive ones)
            DB::table('subscriptions')
                ->where('plan_id', $package->id)
                ->delete();

            // 5. Finally delete the package
            $package->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Package and all related data deleted successfully!'
        ]);
    }

    public function getPackagesByBoardLevel(Request $request)
    {
        $packages = SubscriptionPlan::with(['subjects', 'board', 'level'])
            ->where('board_id', $request->board_id)
            ->where('level_id', $request->level_id)
            ->where('status', true)
            ->get();

        return response()->json($packages);
    }

    public function calculateSavings($id)
    {
        $package = SubscriptionPlan::with('subjects')->findOrFail($id);

        $individualTotal = $package->subjects->sum(function ($subject) {
            return $subject->pivot->subject_price;
        });

        $savings = $individualTotal - $package->price;
        $savingsPercentage = $individualTotal > 0 ? ($savings / $individualTotal) * 100 : 0;

        return response()->json([
            'individual_total' => number_format($individualTotal, 2),
            'package_price' => number_format($package->price, 2),
            'savings' => number_format($savings, 2),
            'savings_percentage' => number_format($savingsPercentage, 2),
            'subject_count' => $package->subjects->count()
        ]);
    }
    public function manageSubjectContent($packageId, $subjectId)
    {
        $package = SubscriptionPlan::findOrFail($packageId);
        $subject = Subject::findOrFail($subjectId);

        if (!$package->subjects()->where('subject_id', $subjectId)->exists()) {
            abort(403);
        }

        $folderHierarchy = $this->buildSubjectContentHierarchy(); // ← Your proven method!

        $assignedIds = DB::table('subject_content')
            ->where('subject_id', $subjectId)
            ->get()
            ->map(fn($item) => $item->folder_id ? 'folder_' . $item->folder_id : 'file_' . $item->media_id)
            ->filter()
            ->values()
            ->toArray();

        return view('admin.packages.subject-content', compact(
            'package',
            'subject',
            'folderHierarchy',
            'assignedIds'
        ));
    }

    // Add this method in your PackageController
    private function buildSubjectContentHierarchy()
    {
        // 1. Get folders with parent info
        $folders = DB::table('folders')
            ->leftJoin('folders as parent', 'folders.parent_id', '=', 'parent.id')
            ->select(
                'folders.id as folder_id',
                'folders.parent_id',
                'folders.name as folder_name',
                'parent.name as parent_folder_name'
            )
            ->get();

        // 2. Get ALL files
        $files = DB::table('files')
            ->select('id', 'folder_id', 'name', 'original_name', 'extension')
            ->get()
            ->groupBy('folder_id');

        $indexed = [];

        // 3. Build folder nodes
        foreach ($folders as $folder) {
            $indexed[$folder->folder_id] = [
                'id'       => 'folder_' . $folder->folder_id,
                'text'     => $folder->folder_name,
                'icon'     => 'fa fa-folder text-warning',
                'children' => [],
                'state'    => ['opened' => true]
            ];

            // Add files inside this folder
            // if (isset($files[$folder->folder_id])) {
            //     foreach ($files[$folder->folder_id] as $file) {
            //         $ext = strtolower($file->extension ?? pathinfo($file->original_name, PATHINFO_EXTENSION));
            //         $indexed[$folder->folder_id]['children'][] = [
            //             'id'   => 'file_' . $file->id,
            //             'text' => $file->name ?? $file->original_name,
            //             'icon' => $this->getFileIcon($ext),
            //         ];
            //     }
            // }
        }

        // 4. Build hierarchy (same logic you already use and love)
        $tree = [];
        foreach ($indexed as $id => &$node) {
            $folder = $folders->firstWhere('folder_id', str_replace('folder_', '', $id));

            if ($folder && $folder->parent_id && isset($indexed[$folder->parent_id])) {
                $indexed[$folder->parent_id]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }

        return $tree;
    }
    private function getFileIcon($ext)
    {
        return match (strtolower($ext)) {
            'pdf' => 'fa fa-file-pdf text-danger',
            'doc', 'docx' => 'fa fa-file-word text-primary',
            'mp4', 'avi', 'mkv' => 'fa fa-file-video text-info',
            'zip', 'rar' => 'fa fa-file-archive text-muted',
            'jpg', 'jpeg', 'png', 'gif' => 'fa fa-file-image text-success',
            default => 'fa fa-file text-secondary',
        };
    }


    private function getAssignedContentIds($subjectId)
    {
        return DB::table('subject_content')
            ->where('subject_id', $subjectId)
            ->get()
            ->map(function ($row) {
                if ($row->folder_id) return 'folder_' . $row->folder_id;
                if ($row->media_id)  return 'file_' . $row->media_id;
                return null;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    public function saveSubjectContent(Request $request, $packageId, $subjectId)
    {
        $request->validate(['content_ids' => 'required|array']);

        DB::table('subject_content')->where('subject_id', $subjectId)->delete();

        $data = [];
        foreach ($request->content_ids as $id) {
            if (str_starts_with($id, 'folder_')) {
                $data[] = ['subject_id' => $subjectId, 'folder_id' => str_replace('folder_', '', $id)];
            } elseif (str_starts_with($id, 'file_')) {
                $data[] = ['subject_id' => $subjectId, 'media_id' => str_replace('file_', '', $id)];
            }
        }

        if ($data) {
            DB::table('subject_content')->insert($data);
        }

        return response()->json(['success' => true]);
    }
     public function romoveSubjectContent(Request $request, $packageId, $subjectId)
    {
        DB::table('subscription_plan_subject')->where('subject_id', $subjectId)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Subject data deleted successfully!'
        ]);
        
    }
     /**
     * Get levels by board ID (AJAX)
     */
    public function getLevelsByBoard(Request $request)
    {
        try {
            $request->validate([
                'board_id' => 'required|exists:boards,id'
            ]);

            $boardId = $request->board_id;
            
            // Get levels for the selected board
            $levels = Level::where('board_id', $boardId)
                ->where('status', true)
                ->orderBy('name')
                ->get()
                ->map(function($level) {
                    return [
                        'id' => $level->id,
                        'name' => $level->name
                    ];
                });

            return response()->json([
                'success' => true,
                'levels' => $levels,
                'count' => $levels->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getLevelsByBoard: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load levels.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
}
