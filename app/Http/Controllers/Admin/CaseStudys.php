<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudies;
use App\Models\Board;
use App\Models\CaseStudyBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class CaseStudys extends Controller
{
    /**
     * Display case studies listing
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view case studies')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getCaseStudiesData();
        }

        $boards = Board::where('status', 'active')->get();
        
        return view('admin.case-study.index', compact('boards'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        if (!auth()->user()->can('create case studies')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $boards = Board::where('status', 'active')->get();
        
        return view('admin.case-study.form', [
            'caseStudy' => null,
            'boards' => $boards,
            'selectedBoards' => [],
            'button' => 'Save'
        ]);
    }

    /**
     * Store new case study
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create case studies')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $this->validateRequest($request);

        try {
            DB::beginTransaction();

            // Handle image upload
            $imageName = $this->handleImageUpload($request);

            // Create case study
            $caseStudy = CaseStudies::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->sections,
                'image' => $imageName,
                'status' => $request->status,
            ]);

            // Attach boards
            if ($request->has('board')) {
                foreach ($request->board as $boardId) {
                    CaseStudyBoard::create([
                        'case_id' => $caseStudy->id,
                        'board_id' => $boardId
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('case-studies.index')
                ->with('success', 'Case study created successfully!');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        if (!auth()->user()->can('edit case studies')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $caseStudy = CaseStudies::findOrFail($id);
        $boards = Board::where('status', 'active')->get();
        
        // Get selected boards
        $selectedBoards = CaseStudyBoard::where('case_id', $id)
            ->pluck('board_id')
            ->toArray();

        return view('admin.case-study.form', [
            'caseStudy' => $caseStudy,
            'boards' => $boards,
            'selectedBoards' => $selectedBoards,
            'button' => 'Update'
        ]);
    }

    /**
     * Update case study
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit case studies')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $this->validateRequest($request, $id);

        try {
            DB::beginTransaction();

            $caseStudy = CaseStudies::findOrFail($id);

            // Handle image upload
            $imageName = $this->handleImageUpload($request, $caseStudy->image);

            // Update case study
            $caseStudy->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->sections,
                'image' => $imageName ?: $caseStudy->image,
                'status' => $request->status,
            ]);

            // Update boards
            CaseStudyBoard::where('case_id', $id)->delete();
            
            if ($request->has('board')) {
                foreach ($request->board as $boardId) {
                    CaseStudyBoard::create([
                        'case_id' => $caseStudy->id,
                        'board_id' => $boardId
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('case-studies.index')
                ->with('success', 'Case study updated successfully!');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete case study
     */
    public function destroy(Request $request)
    {
        if (!auth()->user()->can('delete case studies')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            $id = $request->input('id');
            
            DB::beginTransaction();

            $caseStudy = CaseStudies::findOrFail($id);
            
            // Delete image if exists
            if ($caseStudy->image) {
                Storage::disk('public')->delete('case-study/' . $caseStudy->image);
            }

            // Delete board relationships
            CaseStudyBoard::where('case_id', $id)->delete();
            
            // Delete case study
            $caseStudy->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Case study deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting case study: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get case studies data for DataTable
     */
    private function getCaseStudiesData()
    {
        $query = CaseStudies::query()->with('boards');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<div class="btn-group">';
                $actionBtn .= '<a href="'.route('case-studies.edit', $row->id).'" 
                                class="btn btn-sm btn-primary" title="Edit">
                                <i class="fa fa-edit"></i>
                              </a>';
                $actionBtn .= '<button type="button" 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-id="'.$row->id.'" 
                                data-name="'.$row->title.'"
                                title="Delete">
                                <i class="fa fa-trash"></i>
                              </button>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->addColumn('status_badge', function($row) {
                $badgeClass = $row->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
                return '<span class="'.$badgeClass.'">'.ucfirst($row->status).'</span>';
            })
            ->addColumn('image_preview', function($row) {
                if ($row->image) {
                    return '<img src="'.asset('storage/case-study/' . $row->image).'" 
                            alt="'.$row->title.'" 
                            style="width: 50px; height: 50px; object-fit: cover;">';
                }
                return '-';
            })
            ->addColumn('boards_list', function($row) {
                if ($row->boards->count() > 0) {
                    return $row->boards->pluck('name')->implode(', ');
                }
                return '-';
            })
            ->addColumn('created_at_formatted', function($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
            })
            ->rawColumns(['action', 'status_badge', 'image_preview'])
            ->make(true);
    }

    /**
     * Validate request
     */
    private function validateRequest($request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'nullable|string',
            'board' => 'nullable|array',
            'board.*' => 'exists:boards,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        // For update, make image optional
        if ($id) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $request->validate($rules);
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload($request, $oldImage = null)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Delete old image if exists
            if ($oldImage) {
                Storage::disk('public')->delete('case-study/' . $oldImage);
            }
            
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '-' . uniqid() . '.' . $extension;
            
            // Store file
            $file->storeAs('public/case-study', $filename);
            
            return $filename;
        }
        
        return null;
    }
}