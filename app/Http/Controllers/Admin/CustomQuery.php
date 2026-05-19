<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomeQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CustomQuery extends Controller
{
    /**
     * Display a listing of custom queries.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view custom query')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getCustomQueriesData($request);
        }
        
        return view('admin.custom-queries.index');
    }
    
    /**
     * Get custom queries data for DataTables.
     */
    private function getCustomQueriesData(Request $request)
    {
        try {
            $query = CustomeQuery::query();
            
            // Apply filters if any
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('board', 'like', '%' . $search . '%')
                      ->orWhere('level', 'like', '%' . $search . '%')
                      ->orWhere('subject', 'like', '%' . $search . '%');
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
                    
                    // View button
                    $actionBtn .= '<a href="'.route('custom-queries.show', $row->id).'" 
                                    class="btn btn-primary" title="View">
                                    <i class="fa fa-eye"></i>
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
                ->addColumn('message_preview', function($row) {
                    $preview = Str::limit(strip_tags($row->message), 50);
                    return $preview ?: '<span class="text-muted">No message</span>';
                })
                ->addColumn('created_at_formatted', function($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : 'N/A';
                })
                ->rawColumns(['action', 'message_preview'])
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
     * Show the form for creating a new custom query.
     */
    public function create()
    {
        if (!auth()->user()->can('view custom query')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        return view('admin.custom-queries.form', [
            'page_type' => 'create',
            'query' => null,
            'form_url' => route('custom-queries.store')
        ]);
    }

    /**
     * Store a newly created custom query in storage.
     */
    public function store(Request $request)
    {
        // This is typically used for front-end form submissions
        // Admin might not need to create queries from backend
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'board' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        
        try {
            CustomeQuery::create($validated);
            
            return redirect()->route('custom-queries.index')
                ->with('success', 'Query submitted successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified custom query.
     */
    public function show(CustomeQuery $customQuery)
    {
        if (!auth()->user()->can('view custom query')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        return view('admin.custom-queries.show', [
            'page_type' => 'view',
            'query' => $customQuery,
        ]);
    }

    /**
     * Show the form for editing the specified custom query.
     */
    public function edit(CustomeQuery $customQuery)
    {
        if (!auth()->user()->can('view custom query')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        // Typically custom queries are read-only for admin
        return redirect()->route('custom-queries.show', $customQuery);
    }

    /**
     * Update the specified custom query in storage.
     */
    public function update(Request $request, CustomeQuery $customQuery)
    {
        // Typically custom queries are not updated by admin
        return redirect()->route('custom-queries.index')
            ->with('error', 'Queries cannot be updated.');
    }

    /**
     * Remove the specified custom query from storage.
     */
    public function destroy(CustomeQuery $customQuery)
    {
        if (!auth()->user()->can('delete custom query')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        try {
            $customQuery->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Query deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}