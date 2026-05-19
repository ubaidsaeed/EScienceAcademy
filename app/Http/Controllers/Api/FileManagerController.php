<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ZipArchive;

class FileManagerController extends Controller
{
    // List items
    public function index(Request $request)
    {
        $folderId = $request->get('folder_id');
        $type = $request->get('type');
        $search = $request->get('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');
        $perPage = $request->get('per_page', 50);
        
        $query = FileManager::where('folder_id', $folderId)
            ->where('user_id', Auth::id());
        
        // Filter by type
        if ($type && $type !== 'all') {
            if ($type === 'images') {
                $query->images();
            } elseif ($type === 'documents') {
                $query->documents();
            } elseif ($type === 'archives') {
                $query->archives();
            } elseif ($type === 'folders') {
                $query->folders();
            }
        }
        
        // Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        // Sorting
        $query->orderBy($sort, $order);
        
        // Eager load relationships
        $query->with(['user', 'parent']);
        
        $items = $query->paginate($perPage);
        
        // Transform items for frontend
        $items->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'uuid' => $item->uuid,
                'name' => $item->name,
                'type' => $item->type,
                'mime_type' => $item->mime_type,
                'extension' => $item->extension,
                'size' => $item->readable_size,
                'size_bytes' => $item->size,
                'is_folder' => $item->isFolder(),
                'is_image' => $item->isImage(),
                'is_pdf' => $item->isPdf(),
                'is_excel' => $item->isExcel(),
                'is_archive' => $item->isArchive(),
                'can_preview' => $item->canPreview(),
                'url' => $item->isFile() ? $item->url : null,
                'thumbnail' => $item->thumbnail_url,
                'icon' => $item->getFileIcon(),
                'description' => $item->description,
                'downloads' => $item->downloads,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                'user' => $item->user ? $item->user->name : null,
                'has_children' => $item->children()->exists()
            ];
        });
        
        return response()->json([
            'items' => $items,
            'current_folder' => $folderId ? FileManager::find($folderId) : null,
            'breadcrumbs' => $this->getBreadcrumbs($folderId),
            'stats' => $this->getFolderStats($folderId)
        ]);
    }
    
    // Upload files
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:51200', // 50MB max per file
            'folder_id' => 'nullable|exists:file_manager,id',
            'description' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $files = $request->file('files');
        $folderId = $request->input('folder_id');
        $description = $request->input('description');
        
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            $filename = $this->generateUniqueFilename($file->getClientOriginalName());
            $path = $file->storeAs('uploads/' . date('Y/m'), $filename, 'public');
            
            // Create thumbnail for images
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $this->createThumbnail($path, $file);
            }
            
            $fileRecord = FileManager::create([
                'uuid' => Str::uuid(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => 'file',
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'folder_id' => $folderId,
                'user_id' => Auth::id(),
                'description' => $description
            ]);
            
            $uploadedFiles[] = $fileRecord;
        }
        
        return response()->json([
            'success' => true,
            'message' => count($files) . ' file(s) uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }
    
    // Create thumbnail for images
    private function createThumbnail($path, $file)
    {
        try {
            $thumbnailPath = 'thumbnails/' . $path;
            $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);
            
            // Create directory if not exists
            Storage::disk('public')->makeDirectory(dirname($thumbnailPath));
            
            // Create thumbnail
            Image::make($file)
                ->fit(200, 200)
                ->save($thumbnailFullPath);
                
        } catch (\Exception $e) {
            \Log::error('Thumbnail creation failed: ' . $e->getMessage());
        }
    }
    
    // Generate unique filename
    private function generateUniqueFilename($originalName)
    {
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $slug = Str::slug($name);
        
        $counter = 1;
        $filename = $slug . '.' . $extension;
        
        while (Storage::disk('public')->exists('uploads/' . date('Y/m') . '/' . $filename)) {
            $filename = $slug . '-' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $filename;
    }
    
    // Create folder
    public function createFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'folder_id' => 'nullable|exists:file_manager,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Check if folder already exists
        $existing = FileManager::where('name', $request->name)
            ->where('folder_id', $request->folder_id)
            ->where('type', 'folder')
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existing) {
            return response()->json(['error' => 'Folder already exists'], 422);
        }
        
        $folder = FileManager::create([
            'uuid' => Str::uuid(),
            'name' => $request->input('name'),
            'path' => 'folder_' . time() . '_' . Str::slug($request->input('name')),
            'type' => 'folder',
            'folder_id' => $request->input('folder_id'),
            'user_id' => Auth::id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Folder created successfully',
            'folder' => $folder
        ]);
    }
    
    // Rename item
    public function rename(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $item = FileManager::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $item->name = $request->input('name');
        $item->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Renamed successfully'
        ]);
    }
    
    // Delete items
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:file_manager,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $items = FileManager::whereIn('id', $request->ids)
            ->where('user_id', Auth::id())
            ->get();
        
        foreach ($items as $item) {
            $this->deleteItem($item);
        }
        
        return response()->json([
            'success' => true,
            'message' => count($items) . ' item(s) deleted successfully'
        ]);
    }
    
    // Delete item recursively
    private function deleteItem(FileManager $item)
    {
        // Delete children first if it's a folder
        if ($item->isFolder()) {
            $children = FileManager::where('folder_id', $item->id)
                ->where('user_id', Auth::id())
                ->get();
                
            foreach ($children as $child) {
                $this->deleteItem($child);
            }
        }
        
        // Delete physical file
        if ($item->isFile()) {
            if (Storage::disk('public')->exists($item->path)) {
                Storage::disk('public')->delete($item->path);
            }
            
            // Delete thumbnail if exists
            $thumbnailPath = 'thumbnails/' . $item->path;
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
        
        $item->delete();
    }
    
    // Move items
    public function move(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:file_manager,id',
            'target_folder_id' => 'nullable|exists:file_manager,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $targetFolderId = $request->input('target_folder_id');
        
        // Check for circular reference
        if ($targetFolderId) {
            $items = FileManager::whereIn('id', $request->ids)->get();
            foreach ($items as $item) {
                if ($item->isFolder() && $this->isChildOrSelf($item, $targetFolderId)) {
                    return response()->json([
                        'error' => 'Cannot move folder into itself or its children'
                    ], 422);
                }
            }
        }
        
        FileManager::whereIn('id', $request->ids)
            ->where('user_id', Auth::id())
            ->update(['folder_id' => $targetFolderId]);
        
        return response()->json([
            'success' => true,
            'message' => 'Items moved successfully'
        ]);
    }
    
    // Download file
    public function download($uuid)
    {
        $file = FileManager::where('uuid', $uuid)->firstOrFail();
        
        // Check permissions
        if ($file->user_id != Auth::id() && $file->visibility !== 'public') {
            abort(403);
        }
        
        $file->incrementDownloads();
        
        return Storage::disk('public')->download($file->path, $file->name);
    }
    
    // Download multiple as zip
    public function downloadMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:file_manager,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $items = FileManager::whereIn('id', $request->ids)
            ->where('user_id', Auth::id())
            ->get();
        
        if ($items->isEmpty()) {
            return response()->json(['error' => 'No files selected'], 422);
        }
        
        $zip = new ZipArchive;
        $zipName = 'download_' . time() . '.zip';
        $zipPath = Storage::disk('local')->path('temp/' . $zipName);
        
        // Create temp directory if not exists
        Storage::disk('local')->makeDirectory('temp');
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($items as $item) {
                if ($item->isFile() && Storage::disk('public')->exists($item->path)) {
                    $filePath = Storage::disk('public')->path($item->path);
                    $zip->addFile($filePath, $item->name);
                } elseif ($item->isFolder()) {
                    $this->addFolderToZip($item, $zip, $item->name . '/');
                }
            }
            $zip->close();
        }
        
        foreach ($items as $item) {
            $item->incrementDownloads();
        }
        
        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
    
    // Add folder to zip recursively
    private function addFolderToZip($folder, $zip, $basePath = '')
    {
        $children = FileManager::where('folder_id', $folder->id)
            ->where('user_id', Auth::id())
            ->get();
            
        foreach ($children as $child) {
            if ($child->isFile() && Storage::disk('public')->exists($child->path)) {
                $filePath = Storage::disk('public')->path($child->path);
                $zip->addFile($filePath, $basePath . $child->name);
            } elseif ($child->isFolder()) {
                $this->addFolderToZip($child, $zip, $basePath . $child->name . '/');
            }
        }
    }
    
    // Preview file
    public function preview($uuid)
    {
        $file = FileManager::where('uuid', $uuid)->firstOrFail();
        
        if (!$file->canPreview()) {
            abort(404);
        }
        
        // Check permissions
        if ($file->user_id != Auth::id() && $file->visibility !== 'public') {
            abort(403);
        }
        
        $file->increment('last_accessed_at', now());
        
        if ($file->isImage()) {
            return response()->file(Storage::disk('public')->path($file->path));
        }
        
        if ($file->isPdf()) {
            return response()->file(Storage::disk('public')->path($file->path), [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $file->name . '"'
            ]);
        }
        
        // For text files
        $content = Storage::disk('public')->get($file->path);
        return response($content)->header('Content-Type', 'text/plain');
    }
    
    // Get breadcrumbs
    private function getBreadcrumbs($folderId)
    {
        $breadcrumbs = [];
        
        if ($folderId) {
            $current = FileManager::find($folderId);
            while ($current) {
                $breadcrumbs[] = [
                    'id' => $current->id,
                    'name' => $current->name,
                    'type' => $current->type
                ];
                $current = $current->parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
        }
        
        return $breadcrumbs;
    }
    
    // Get folder statistics
    private function getFolderStats($folderId)
    {
        $stats = [
            'total_files' => 0,
            'total_folders' => 0,
            'total_size' => 0,
            'images' => 0,
            'documents' => 0,
            'archives' => 0
        ];
        
        $query = FileManager::where('folder_id', $folderId)
            ->where('user_id', Auth::id());
            
        $items = $query->get();
        
        foreach ($items as $item) {
            if ($item->isFolder()) {
                $stats['total_folders']++;
            } else {
                $stats['total_files']++;
                $stats['total_size'] += $item->size;
                
                if ($item->isImage()) {
                    $stats['images']++;
                } elseif ($item->isPdf() || $item->isExcel()) {
                    $stats['documents']++;
                } elseif ($item->isArchive()) {
                    $stats['archives']++;
                }
            }
        }
        
        $stats['total_size'] = $this->formatBytes($stats['total_size']);
        
        return $stats;
    }
    
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    // Check if target is child or self
    private function isChildOrSelf($item, $targetId)
    {
        if (!$targetId || !$item->isFolder()) {
            return false;
        }
        
        if ($item->id == $targetId) {
            return true;
        }
        
        $children = FileManager::where('folder_id', $item->id)
            ->where('type', 'folder')
            ->pluck('id')
            ->toArray();
            
        while (!empty($children)) {
            if (in_array($targetId, $children)) {
                return true;
            }
            
            $children = FileManager::whereIn('folder_id', $children)
                ->where('type', 'folder')
                ->pluck('id')
                ->toArray();
        }
        
        return false;
    }
    
    // Get file info
    public function info($uuid)
    {
        $file = FileManager::where('uuid', $uuid)
            ->with(['user', 'parent'])
            ->firstOrFail();
        
        // Check permissions
        if ($file->user_id != Auth::id() && $file->visibility !== 'public') {
            abort(403);
        }
        
        return response()->json([
            'file' => [
                'id' => $file->id,
                'uuid' => $file->uuid,
                'name' => $file->name,
                'type' => $file->type,
                'mime_type' => $file->mime_type,
                'extension' => $file->extension,
                'size' => $file->readable_size,
                'size_bytes' => $file->size,
                'url' => $file->url,
                'thumbnail' => $file->thumbnail_url,
                'description' => $file->description,
                'is_public' => $file->is_public,
                'visibility' => $file->visibility,
                'downloads' => $file->downloads,
                'created_at' => $file->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $file->updated_at->format('Y-m-d H:i:s'),
                'last_accessed_at' => $file->last_accessed_at?->format('Y-m-d H:i:s'),
                'user' => $file->user ? $file->user->name : null,
                'parent' => $file->parent ? $file->parent->name : null
            ]
        ]);
    }
}