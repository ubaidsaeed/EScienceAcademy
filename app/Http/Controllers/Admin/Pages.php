<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\PageSection;
use Yajra\DataTables\Facades\DataTables;

class Pages extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view page')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
            return view('admin.pages.index');
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create page')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        // if (\Auth::user()->can('create page')) {
            return view('admin.pages.form', ['page' => null]);
        // } else {
        //     return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!auth()->user()->can('edit page')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        // if (\Auth::user()->can('edit page')) {
            $page = Page::findOrFail($id);
            $sections = PageSection::where('page_id', $id)->orderBy('priority', 'asc')->get();
            
            return view('admin.pages.form', compact('page', 'sections'));
        // } else {
        //     return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        // }
    }

    /**
     * Store or Update page (Single method for both)
     */
    public function save(Request $request)
    {
        $isUpdate = $request->has('id') && $request->id;
        
        // Check permissions
        // if ($isUpdate && !\Auth::user()->can('edit page')) {
        //     return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to update pages!');
        // }
        
        // if (!$isUpdate && !\Auth::user()->can('create page')) {
        //     return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to create pages!');
        // }

        // Validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:1024',
        ];

        // Add unique validation for title (skip current page on update)
        if ($isUpdate) {
            $rules['title'] .= '|unique:pages,title,' . $request->id;
        } else {
            $rules['title'] .= '|unique:pages,title';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        DB::beginTransaction();
        try {
            $slug = Str::slug($request->title);
            
            if ($isUpdate) {
                // UPDATE OPERATION
                $page = Page::findOrFail($request->id);
                $message = 'Page updated successfully!';
                
                // Handle image upload
                if ($request->hasFile('thumbnail')) {
                    // Delete old image if exists
                    if ($page->thumbnail && Storage::exists('public/pages/'.$page->thumbnail)) {
                        Storage::delete('public/pages/'.$page->thumbnail);
                    }
                    
                    $thumbnailName = $this->storeImage($request->file('thumbnail'));
                    $page->thumbnail = $thumbnailName;
                }

                // Update page data
                $page->title = $request->title;
                $page->slug = $slug;
                $page->status = $request->status;
                $page->save();

                // Handle sections
                if ($request->has('sections')) {
                    // Delete existing sections
                    PageSection::where('page_id', $page->id)->delete();
                    
                    // Store new sections
                    $this->storeSections($request->sections, $page->id);
                }
            } else {
                // CREATE OPERATION
                $message = 'Page created successfully!';
                
                // Handle image upload
                $thumbnailName = null;
                if ($request->hasFile('thumbnail')) {
                    $thumbnailName = $this->storeImage($request->file('thumbnail'));
                }

                // Create page
                $page = Page::create([
                    'title' => $request->title,
                    'slug' => $slug,
                    'status' => $request->status,
                    'thumbnail' => $thumbnailName,
                ]);

                // Handle sections if provided
                if ($request->has('sections')) {
                    $this->storeSections($request->sections, $page->id);
                }
            }

            DB::commit();
            
            return redirect()->route('pages.list')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Operation failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource for DataTable.
     */
    public function show(Request $request)
    {
        
        if ($request->ajax()) {
            $data = Page::latest()->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (\Auth::user()->can('edit page')) {
                        $buttons .= '<a href="'.route('pages.edit', $row->id).'" class="btn btn-primary btn-sm me-2"><i class="fa fa-edit"></i></a>';
                    }
                    
                    if (\Auth::user()->can('delete page')) {
                        $buttons .= '<button type="button" onclick="deletePage('.$row->id.')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    }
                    
                    return $buttons;
                })
                ->addColumn('status', function($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('thumbnail', function($row) {
                    if ($row->thumbnail) {
                        return '<img src="'.asset('storage/app/public/pages/'.$row->thumbnail).'" alt="Thumbnail" width="50">';
                    }
                    return '-';
                })
                ->rawColumns(['action', 'status', 'thumbnail'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (!auth()->user()->can('delete page')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        // if (\Auth::user()->can('delete page')) {
            try {
                $page = Page::findOrFail($request->id);
                
                // Delete thumbnail if exists
                if ($page->thumbnail && Storage::exists('public/pages/'.$page->thumbnail)) {
                    Storage::delete('public/pages/'.$page->thumbnail);
                }
                
                // Delete sections
                PageSection::where('page_id', $page->id)->delete();
                
                // Delete page
                $page->delete();
                
                return response()->json([
                    'success' => 'Page deleted successfully!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to delete page: ' . $e->getMessage()
                ], 500);
            }
        // } else {
        //     return response()->json([
        //         'error' => 'You are not authorized to delete pages'
        //     ], 403);
        // }
    }

    /**
     * Delete a section
     */
    public function removeSection($id)
    {
        // if (\Auth::user()->can('edit page')) {
            try {
                PageSection::findOrFail($id)->delete();
                
                return response()->json([
                    'success' => 'Section deleted successfully!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to delete section: ' . $e->getMessage()
                ], 500);
            }
        // } else {
        //     return response()->json([
        //         'error' => 'You are not authorized to delete sections'
        //     ], 403);
        // }
    }
    // In PagesController.php

/**
 * Check if slug is unique
 */
public function checkSlug(Request $request)
{
    $validator = Validator::make($request->all(), [
        'slug' => 'required|string|max:255',
        'page_id' => 'nullable|integer|exists:pages,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => 'Invalid input'
        ], 400);
    }

    $slug = $request->slug;
    $pageId = $request->page_id;
    
    // Check if slug exists (excluding current page if editing)
    $query = Page::where('slug', $slug);
    
    if ($pageId) {
        $query->where('id', '!=', $pageId);
    }
    
    $exists = $query->exists();
    
    if ($exists) {
        // Generate alternative suggestions
        $suggestions = [];
        for ($i = 1; $i <= 5; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!Page::where('slug', $newSlug)->exists()) {
                $suggestions[] = $newSlug;
                if (count($suggestions) >= 3) break;
            }
        }
        
        return response()->json([
            'available' => false,
            'suggestions' => $suggestions
        ]);
    }
    
    return response()->json([
        'available' => true
    ]);
}

    /**
     * Store image
     */
    private function storeImage($image)
    {
        $ext = $image->getClientOriginalExtension();
        $newName = time() . '-' . rand(1000, 1000000) . '.' . $ext;
        $image->storeAs('public/pages', $newName);
        return $newName;
    }

    /**
     * Store sections
     */
    private function storeSections($sections, $pageId)
    {
        foreach ($sections as $section) {
            if (!empty($section['content_type'])) {
                PageSection::create([
                    'page_id' => $pageId,
                    'page_data' => $section['page_data'] ?? '',
                    'content_type' => $section['content_type'],
                    'priority' => $section['priority'] ?? 0,
                ]);
            }
        }
    }

    /**
     * Upload image for TinyMCE editor
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $image = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/editor-images', $filename);
            
            return response()->json([
                'location' => asset('storage/editor-images/' . $filename)
            ]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }
     // In PagesController.php

/**
 * Preview a page by slug (public preview)
 */
public function preview($slug)
{
    try {
        $page = Page::where('slug', $slug)
            ->where('status', 'active')
            ->with(['sections' => function($query) {
                $query->orderBy('priority', 'asc');
            }])
            ->firstOrFail();
            dd($page);
        // Check if user can view the page
        // if (!\Auth::check() && $page->status !== 'active') {
        //     abort(404);
        // }
        
        return view('front-end.preview', compact('page'));
        
    } catch (\Exception $e) {
        abort(404);
    }
}

/**
 * Live preview during editing (admin only)
 */
public function livePreview($id)
{
    
    if (!\Auth::user()->can('edit pages')) {
        abort(403, 'Unauthorized');
    }
    
    try {
        $page = Page::with(['sections' => function($query) {
                $query->orderBy('priority', 'asc');
            }])
            ->findOrFail($id);
            
        // Use a special layout for live preview
        return view('admin.pages.live-preview', compact('page'));
        
    } catch (\Exception $e) {
        abort(404);
    }
}

/**
 * Get preview URL for AJAX
 */
public function getPreviewUrl(Request $request)
{
    $validator = FacadesValidator::make($request->all(), [
        'slug' => 'required|string',
        'id' => 'nullable|exists:pages,id'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => 'Invalid input'], 400);
    }

    $slug = $request->slug;
    $pageId = $request->id;
    
    // Check if slug is unique (excluding current page)
    $query = Page::where('slug', $slug);
    if ($pageId) {
        $query->where('id', '!=', $pageId);
    }
    
    $exists = $query->exists();
    
    if ($exists) {
        return response()->json([
            'available' => false,
            'preview_url' => null
        ]);
    }
    
    // Generate preview URL
    $previewUrl = route('pages.preview', ['slug' => $slug]);
    
    // For existing pages, also provide live preview URL
    $livePreviewUrl = null;
    if ($pageId) {
        $livePreviewUrl = route('pages.live-preview', ['id' => $pageId]);
    }
    
    return response()->json([
        'available' => true,
        'preview_url' => $previewUrl,
        'live_preview_url' => $livePreviewUrl
    ]);
}
}