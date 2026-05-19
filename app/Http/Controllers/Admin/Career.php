<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Careers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class Career extends Controller
{
    /**
     * Display career applications listing
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view careers')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getCareerData();
        }

        return view('admin.career.index');
    }

    /**
     * View career application details
     */
    public function show($id)
    {
        if (!auth()->user()->can('view careers')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }

        $career = Careers::findOrFail($id);
        
        return view('admin.career.show', compact('career'));
    }

    /**
     * Delete career application
     */
    public function destroy(Request $request)
    {
        if (!auth()->user()->can('delete careers')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if (!auth()->user()->can('delete career')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete career applications.'
            ], 403);
        }

        try {
            $id = $request->input('id');
            $career = Careers::findOrFail($id);
            
            // Delete resume file if exists
            if ($career->resume && Storage::exists('public/careers/' . $career->resume)) {
                Storage::delete('public/careers/' . $career->resume);
            }
            
            $career->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Career application deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting career application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download resume
     */
    public function downloadResume($id)
    {
        if (!auth()->user()->can('view careers')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if (!auth()->user()->can('view career')) {
            return redirect()->back()
                ->with('error', 'You are not authorized to download resumes.');
        }

        $career = Careers::findOrFail($id);
        
        if (!$career->resume || !Storage::exists('public/careers/' . $career->resume)) {
            return redirect()->back()
                ->with('error', 'Resume file not found.');
        }
        
        $filePath = storage_path('app/public/careers/' . $career->resume);
        $fileName = $career->name . '_resume_' . $career->resume;
        
        return response()->download($filePath, $fileName);
    }

    /**
     * Get career data for DataTable
     */
    private function getCareerData()
    {
        
        $query = Careers::query()->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<div class="btn-group">';
                
                // View button
                $actionBtn .= '<a href="'.route('careers.show', $row->id).'" 
                                class="btn btn-sm btn-info" title="View Details">
                                <i class="fa fa-eye"></i>
                              </a>';
                
                // Download resume button
                $actionBtn .= '<a href="'.route('careers.download', $row->id).'" 
                                class="btn btn-sm btn-success" title="Download Resume">
                                <i class="fa fa-download"></i>
                              </a>';
                
                // Delete button
                $actionBtn .= '<button type="button" 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-id="'.$row->id.'" 
                                data-name="'.$row->name.'"
                                title="Delete">
                                <i class="fa fa-trash"></i>
                              </button>';
                
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->addColumn('resume_link', function($row) {
                if ($row->resume) {
                    return '<a href="'.route('careers.download', $row->id).'" 
                            class="text-primary" target="_blank">
                            <i class="fa fa-file"></i> Download
                           </a>';
                }
                return 'No Resume';
            })
            ->addColumn('created_at_formatted', function($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
            })
            ->addColumn('cover_letter_preview', function($row) {
                return strlen($row->cover_letter) > 100 
                    ? substr($row->cover_letter, 0, 100) . '...' 
                    : $row->cover_letter;
            })
            ->rawColumns(['action', 'resume_link', 'cover_letter_preview'])
            ->make(true);
    }
}