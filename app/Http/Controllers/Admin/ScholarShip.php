<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipRequests;
use App\Models\ScholarshipAchievement;
use App\Models\ScholarshipRequestAchievements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class Scholarship extends Controller
{
    // Store URL: /admin/scholarship/create
    // Update URL: /admin/scholarship/{id}/edit
    // View URL: /admin/scholarship

    public function index(Request $request)
    {
        if (!auth()->user()->can('view settings')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getScholarshipData();
        }
        return view('admin.scholarship.index', [
            'page_type' => 'view'
        ]);
    }

    public function create()
    {
        
        return view('admin.scholarship.form', [
            'scholarship' => null,
            'page_type' => 'create',
            'achievements' => collect()
        ]);
    }

    public function edit($id)
    {
        $scholarship = ScholarshipRequests::with('achievements')->findOrFail($id);

        return view('admin.scholarship.form', [
            'scholarship' => $scholarship,
            'page_type' => 'edit',
            'achievements' => $scholarship->achievements
        ]);
    }

    public function show($id)
    {
        $scholarship = ScholarshipRequests::with('achievements')->findOrFail($id);

        return view('admin.scholarship.show', [
            'scholarship' => $scholarship,
            'page_type' => 'view_details',

            'achievements' => $scholarship->achievements
        ]);
    }

    /**
     * Handle both store and update operations
     */
    public function save(Request $request, $id = null)
    {
        if (!auth()->user()->can('edit settings')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            DB::beginTransaction();

            // Get validated data
            $validated = $this->validateRequest($request, $id);

            if ($id) {
                // Update existing record
                $scholarship = ScholarshipRequests::findOrFail($id);
                $this->handleFileUpload($request, $validated, $scholarship);
                $scholarship->update($validated);
                $message = 'Scholarship request updated successfully!';
            } else {
                // Create new record - handle file uploads first
                $this->handleFileUpload($request, $validated);
                $scholarship = ScholarshipRequests::create($validated);
                $id = $scholarship->id;
                $message = 'Scholarship request created successfully!';
            }

            // Handle achievements
            // $this->handleAchievements($request, $scholarship);

            DB::commit();

            return redirect()
                ->route('admin.scholarship.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(ScholarshipRequests $scholarship)
    {
        try {
            // Delete related achievements first
            ScholarshipRequestAchievements::where('scholarship_id', $scholarship->id)->delete();

            // Delete files if needed
            $this->deleteFiles($scholarship);

            $scholarship->delete();

            return response()->json([
                'success' => true,
                'message' => 'Scholarship request deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Common validation rules for both store and update
     */
    private function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string',
        ];

        // File validation rules
        $fileRules = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';

        if (!$id) {
            // For create, files are required
            $rules['parents_id_card'] = 'required|' . substr($fileRules, 9);
            $rules['electricity_bills'] = 'required|' . substr($fileRules, 9);
            $rules['academic_transcripts'] = 'required|' . substr($fileRules, 9);
            $rules['parental_bank_certificate'] = 'required|' . substr($fileRules, 9);
        } else {
            // For update, files are optional
            $rules['parents_id_card'] = $fileRules;
            $rules['electricity_bills'] = $fileRules;
            $rules['academic_transcripts'] = $fileRules;
            $rules['parental_bank_certificate'] = $fileRules;
        }

        $rules['achievements.*'] = $fileRules;

        return $request->validate($rules);
    }

    /**
     * Handle file uploads
     */
    private function handleFileUpload(Request $request, array &$validated, $scholarship = null)
    {
        $fileFields = [
            'parents_id_card',
            'electricity_bills',
            'academic_transcripts',
            'parental_bank_certificate'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($scholarship && $scholarship->$field) {
                    Storage::disk('public')->delete($scholarship->$field);
                }

                $validated[$field] = $request->file($field)->store('scholarships/' . $field, 'public');
            } elseif ($scholarship && !isset($validated[$field])) {
                // Keep existing file if not updating
                $validated[$field] = $scholarship->$field;
            }
        }
    }

    /**
     * Handle file uploads for achievements
     */
    private function handleAchievements(Request $request, $scholarship)
    {
        if ($request->hasFile('achievements')) {
            // Delete old achievements if updating
            if ($scholarship->id) {
                $oldAchievements = ScholarshipRequestAchievements::where('scholarship_request_id', $scholarship->id)->get();
                foreach ($oldAchievements as $achievement) {
                    if ($achievement->achievement) {
                        Storage::disk('public')->delete($achievement->achievement);
                    }
                }
                ScholarshipRequestAchievements::where('scholarship_id', $scholarship->id)->delete();
            }

            foreach ($request->file('achievements') as $achievementFile) {
                if ($achievementFile->isValid()) {
                    $path = $achievementFile->store('scholarships/achievements', 'public');

                    ScholarshipRequestAchievements::create([
                        'scholarship_request_id' => $scholarship->id,
                        'achievement' => $path
                    ]);
                }
            }
        }
    }

    /**
     * Delete files when deleting scholarship
     */
    private function deleteFiles($scholarship)
    {
        $fileFields = [
            'parents_id_card',
            'electricity_bills',
            'academic_transcripts',
            'parental_bank_certificate'
        ];

        foreach ($fileFields as $field) {
            if ($scholarship->$field) {
                Storage::disk('public')->delete($scholarship->$field);
            }
        }

        // Delete achievement files
        foreach ($scholarship->achievements as $achievement) {
            if ($achievement->achievement) {
                Storage::disk('public')->delete($achievement->achievement);
            }
        }
    }

    /**
     * Datatable implementation following your pattern
     */
    private function getScholarshipData()
    {
        $query = ScholarshipRequests::query()->with('achievements');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<div class="btn-group">';
                if (auth()->user()->can('view scholarship')) {
                $actionBtn .= '<a href="' . route('admin.scholarship.show', $row->id) . '" 
                                class="btn btn-sm btn-info" title="View Details">
                                <i class="fa fa-eye"></i>
                              </a>';
                }
                if (auth()->user()->can('edit scholarship')) {
                $actionBtn .= '<a href="' . route('admin.scholarship.edit', $row->id) . '" 
                                class="btn btn-sm btn-primary" title="Edit">
                                <i class="fa fa-edit"></i>
                              </a>';
                }
                if (auth()->user()->can('delete scholarship')) {
                $actionBtn .= '<button type="button" 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-id="' . $row->id . '" 
                                data-name="' . $row->name . '"
                                title="Delete">
                                <i class="fa fa-trash"></i>
                              </button>';
                }
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->addColumn('achievements_count', function ($row) {
                return '<span class="badge bg-info">' . $row->achievements->count() . '</span>';
            })
            ->addColumn('created_at_formatted', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
            })
            ->addColumn('files', function ($row) {
                $files = [];
                if ($row->parents_id_card) $files[] = 'Parents ID';
                if ($row->electricity_bills) $files[] = 'Electricity Bill';
                if ($row->academic_transcripts) $files[] = 'Transcripts';
                if ($row->parental_bank_certificate) $files[] = 'Bank Certificate';

                return count($files) . ' file(s)';
            })
            ->rawColumns(['action', 'achievements_count'])
            ->make(true);
    }
}
