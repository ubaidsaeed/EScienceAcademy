<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FileManagerController extends Controller
{
    /**
     * Display the file manager page.
     */
    public function index()
    {
        // Set session flag to allow file access from filemanager
        session(['filemanager_access' => true, 'filemanager_access_time' => time()]);
        
        return view('admin.filemanager.index');
    }

    /**
     * Get folders and files for a given folder.
     */
    public function getItems(Request $request)
    {
        $folderId = $request->input('folder_id', null);
        
        $folders = Folder::where('parent_id', $folderId)
            ->orderBy('name')
            ->get();
        
        $files = File::where('folder_id', $folderId)
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'folders' => $folders,
            'files' => $files,
        ]);
    }

    /**
     * Get breadcrumb path.
     */
    public function getBreadcrumb(Request $request)
    {
        $folderId = $request->input('folder_id', null);
        $breadcrumb = [];
        
        if ($folderId) {
            $folder = Folder::find($folderId);
            while ($folder) {
                array_unshift($breadcrumb, [
                    'id' => $folder->id,
                    'name' => $folder->name,
                ]);
                $folder = $folder->parent;
            }
        }
        
        return response()->json($breadcrumb);
    }

    /**
     * Create a new folder.
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $parentId = $request->input('parent_id', null);
        $parentPath = '';
        
        if ($parentId) {
            $parent = Folder::find($parentId);
            $parentPath = $parent->path ? $parent->path . '/' : '';
        }

        $folder = Folder::create([
            'name' => $request->name,
            'parent_id' => $parentId,
            'path' => $parentPath . Str::slug($request->name),
        ]);

        // Create physical folder
        $physicalPath = 'filemanager/' . $folder->path;
        Storage::disk('public')->makeDirectory($physicalPath);

        return response()->json([
            'success' => true,
            'folder' => $folder,
        ]);
    }

    /**
     * Upload files.
     */
    public function uploadFiles(Request $request)
    {
        $request->validate([
            'files.*' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    $mimeType = $value->getMimeType();
                    $allowedMimes = [
                        // Images
                        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 
                        'image/bmp', 'image/svg+xml', 'image/tiff', 'image/x-icon',
                        // PDF
                        'application/pdf'
                    ];
                    
                    if (!in_array($mimeType, $allowedMimes)) {
                        $fail('Only image files (JPEG, PNG, GIF, WEBP, BMP, SVG, TIFF, ICO) and PDF documents are allowed.');
                    }
                },
            ],
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $folderId = $request->input('folder_id', null);
        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            $folderPath = '';
            if ($folderId) {
                $folder = Folder::find($folderId);
                $folderPath = $folder->path ? $folder->path . '/' : '';
            }

            $storagePath = 'filemanager/' . ($folderPath ? $folderPath : '');
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($storagePath);

            foreach ($request->file('files') as $uploadedFile) {
                $originalName = $uploadedFile->getClientOriginalName();
                $extension = $uploadedFile->getClientOriginalExtension();
                $fileName = Str::random(40) . '.' . $extension;
                $path = $storagePath . $fileName;

                $uploadedFile->storeAs($storagePath, $fileName, 'public');

                $file = File::create([
                    'name' => $fileName,
                    'original_name' => $originalName,
                    'path' => $path,
                    'mime_type' => $uploadedFile->getMimeType(),
                    'size' => $uploadedFile->getSize(),
                    'folder_id' => $folderId,
                    'extension' => $extension,
                ]);

                $uploadedFiles[] = $file;
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
        ]);
    }
// public function uploadFiles(Request $request)
// {
//     // For chunked uploads, we'll handle differently
//     if ($request->has('chunk')) {
//         return $this->handleChunkedUpload($request);
//     }

//     // Fallback for small files (under 100MB)
//     return $this->handleStandardUpload($request);
// }

// private function handleChunkedUpload(Request $request)
// {
//     $request->validate([
//         'file' => 'required|file',
//         'chunk' => 'required|integer',
//         'chunks' => 'required|integer',
//         'uuid' => 'required|string',
//         'original_name' => 'required|string',
//         'mime_type' => 'required|string',
//         'folder_id' => 'nullable|exists:folders,id',
//     ]);

//     // Validate MIME type
//     $allowedMimes = [
//         'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 
//         'image/bmp', 'image/svg+xml', 'image/tiff', 'image/x-icon',
//         'application/pdf',
//         'video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo',
//         'application/zip', 'application/x-rar-compressed',
//         'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
//     ];
    
//     if (!in_array($request->mime_type, $allowedMimes)) {
//         return response()->json([
//             'success' => false,
//             'message' => 'File type not allowed.'
//         ], 415);
//     }

//     // Set folder path
//     $folderId = $request->input('folder_id', null);
//     $folderPath = '';
//     if ($folderId) {
//         $folder = Folder::find($folderId);
//         $folderPath = $folder->path ? $folder->path . '/' : '';
//     }

//     $storagePath = 'filemanager/tmp/' . $request->uuid;
//     $chunkPath = $storagePath . '/chunk_' . $request->chunk;
    
//     // Store the chunk
//     Storage::disk('public')->put($chunkPath, file_get_contents($request->file('file')->getRealPath()));

//     // If this is the last chunk, combine all chunks
//     if ($request->chunk == ($request->chunks - 1)) {
//         return $this->combineChunks($request, $folderPath);
//     }

//     return response()->json([
//         'success' => true,
//         'chunk' => $request->chunk,
//         'message' => 'Chunk uploaded successfully'
//     ]);
// }

// private function combineChunks(Request $request, string $folderPath)
// {
//     $uuid = $request->uuid;
//     $totalChunks = $request->chunks;
//     $originalName = $request->original_name;
//     $mimeType = $request->mime_type;
    
//     $tmpPath = 'filemanager/tmp/' . $uuid;
//     $finalStoragePath = 'filemanager/' . ($folderPath ? $folderPath : '');
//     $extension = pathinfo($originalName, PATHINFO_EXTENSION);
//     $fileName = Str::random(40) . '.' . $extension;
//     $finalPath = $finalStoragePath . $fileName;
    
//     // Ensure final directory exists
//     Storage::disk('public')->makeDirectory($finalStoragePath);
    
//     // Open final file for writing
//     $finalFile = fopen(Storage::disk('public')->path($finalPath), 'wb');
    
//     // Combine all chunks
//     for ($i = 0; $i < $totalChunks; $i++) {
//         $chunkPath = $tmpPath . '/chunk_' . $i;
        
//         if (Storage::disk('public')->exists($chunkPath)) {
//             $chunkContent = Storage::disk('public')->get($chunkPath);
//             fwrite($finalFile, $chunkContent);
//             // Delete chunk after merging
//             Storage::disk('public')->delete($chunkPath);
//         }
//     }
    
//     fclose($finalFile);
    
//     // Delete temporary directory
//     Storage::disk('public')->deleteDirectory($tmpPath);
    
//     // Calculate file size
//     $fileSize = Storage::disk('public')->size($finalPath);
    
//     // Create file record
//     $file = File::create([
//         'name' => $fileName,
//         'original_name' => $originalName,
//         'path' => $finalPath,
//         'mime_type' => $mimeType,
//         'size' => $fileSize,
//         'folder_id' => $request->folder_id,
//         'extension' => $extension,
//     ]);

//     return response()->json([
//         'success' => true,
//         'file' => $file,
//         'message' => 'File uploaded successfully'
//     ]);
// }

// private function handleStandardUpload(Request $request)
// {
//     $request->validate([
//         'files.*' => [
//             'required',
//             'file',
//             'max:104857600', // 100MB max for standard upload
//             function ($attribute, $value, $fail) {
//                 $mimeType = $value->getMimeType();
//                 $allowedMimes = [
//                     'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 
//                     'image/bmp', 'image/svg+xml', 'image/tiff', 'image/x-icon',
//                     'application/pdf',
//                     'video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo',
//                     'application/zip', 'application/x-rar-compressed',
//                     'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
//                 ];
                
//                 if (!in_array($mimeType, $allowedMimes)) {
//                     $fail('File type not allowed.');
//                 }
//             },
//         ],
//         'folder_id' => 'nullable|exists:folders,id',
//     ]);

//     $folderId = $request->input('folder_id', null);
//     $uploadedFiles = [];

//     if ($request->hasFile('files')) {
//         $folderPath = '';
//         if ($folderId) {
//             $folder = Folder::find($folderId);
//             $folderPath = $folder->path ? $folder->path . '/' : '';
//         }

//         $storagePath = 'filemanager/' . ($folderPath ? $folderPath : '');
//         Storage::disk('public')->makeDirectory($storagePath);

//         foreach ($request->file('files') as $uploadedFile) {
//             $originalName = $uploadedFile->getClientOriginalName();
//             $extension = $uploadedFile->getClientOriginalExtension();
//             $fileName = Str::random(40) . '.' . $extension;
//             $path = $storagePath . $fileName;

//             // Use stream for better memory management
//             $stream = fopen($uploadedFile->getRealPath(), 'r+');
//             Storage::disk('public')->writeStream($path, $stream);
//             if (is_resource($stream)) {
//                 fclose($stream);
//             }

//             $file = File::create([
//                 'name' => $fileName,
//                 'original_name' => $originalName,
//                 'path' => $path,
//                 'mime_type' => $uploadedFile->getMimeType(),
//                 'size' => $uploadedFile->getSize(),
//                 'folder_id' => $folderId,
//                 'extension' => $extension,
//             ]);

//             $uploadedFiles[] = $file;
//         }
//     }

//     return response()->json([
//         'success' => true,
//         'files' => $uploadedFiles,
//     ]);
// }
    /**
     * Upload Vimeo video link.
     */
    public function uploadVimeoLink(Request $request)
    {
        $request->validate([
            'vimeo_url' => 'required|url',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $vimeoUrl = $request->input('vimeo_url');
        $folderId = $request->input('folder_id', null);
        $accessToken = config('services.vimeo.access_token');

        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Vimeo access token not configured. Please set VIMEO_ACCESS_TOKEN in your .env file.',
            ], 400);
        }

        // Extract Vimeo video ID from URL
        $vimeoId = $this->extractVimeoId($vimeoUrl);
        
        if (!$vimeoId) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Vimeo URL. Please provide a valid Vimeo video link.',
            ], 400);
        }

        try {
            // Fetch video data from Vimeo API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/vnd.vimeo.*+json;version=3.4',
            ])->get("https://api.vimeo.com/videos/{$vimeoId}?fields=name,pictures.sizes,embed.html,privacy.view,privacy.embed");

            if (!$response->successful()) {
                $errorData = $response->json();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch video from Vimeo: ' . ($errorData['error'] ?? 'Unknown error') . '. Please check your access token has "private" scope and the video ID is correct.',
                ], 400);
            }

            $videoData = $response->json();
            $videoName = $videoData['name'] ?? 'Vimeo Video';
            
            // Check embed privacy settings
            $embedPrivacy = $videoData['privacy']['embed'] ?? null;
            if ($embedPrivacy === 'private') {
                return response()->json([
                    'success' => false,
                    'message' => 'This video has private embed settings. Please go to Vimeo video settings → Privacy → "Where can this be embedded?" and change it to "Anywhere".',
                ], 400);
            }
            
            // Warn if embed is restricted to specific domains
            if ($embedPrivacy === 'whitelist') {
                // Still allow, but user should know they need to whitelist their domain
            }
            
            // Get thumbnail - try to get the largest available
            $thumbnailUrl = null;
            if (isset($videoData['pictures']['sizes']) && is_array($videoData['pictures']['sizes'])) {
                $sizes = $videoData['pictures']['sizes'];
                // Get the last (largest) thumbnail
                $thumbnailUrl = end($sizes)['link'] ?? null;
            }

            // Download thumbnail
            $thumbnailPath = null;
            if ($thumbnailUrl) {
                $thumbnailPath = $this->downloadVimeoThumbnail($thumbnailUrl, $vimeoId, $folderId);
            }

            // Create file record
            $file = File::create([
                'name' => 'vimeo_' . $vimeoId . '.jpg',
                'original_name' => $videoName,
                'path' => $thumbnailPath ?? 'vimeo/' . $vimeoId . '.jpg',
                'mime_type' => 'video/vimeo',
                'size' => 0,
                'folder_id' => $folderId,
                'extension' => 'vimeo',
                'is_vimeo' => true,
                'vimeo_id' => $vimeoId,
                'vimeo_url' => $vimeoUrl,
                'vimeo_thumbnail_url' => $thumbnailUrl,
            ]);

            return response()->json([
                'success' => true,
                'file' => $file,
                'message' => 'Vimeo video added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing Vimeo video: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract Vimeo video ID from URL.
     */
    private function extractVimeoId($url)
    {
        // Match various Vimeo URL formats
        // https://vimeo.com/123456789
        // https://player.vimeo.com/video/123456789
        // https://vimeo.com/channels/name/123456789
        if (preg_match('/vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/videos\/|album\/\d+\/video\/|video\/|)(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Download Vimeo thumbnail.
     */
    private function downloadVimeoThumbnail($thumbnailUrl, $vimeoId, $folderId = null)
    {
        try {
            $folderPath = '';
            if ($folderId) {
                $folder = Folder::find($folderId);
                $folderPath = $folder->path ? $folder->path . '/' : '';
            }

            $storagePath = 'filemanager/vimeo/' . ($folderPath ? $folderPath : '');
            Storage::disk('public')->makeDirectory($storagePath);

            $thumbnailName = 'vimeo_' . $vimeoId . '_thumb.jpg';
            $thumbnailPath = $storagePath . $thumbnailName;

            // Download thumbnail
            $thumbnailContent = Http::get($thumbnailUrl)->body();
            Storage::disk('public')->put($thumbnailPath, $thumbnailContent);

            return $thumbnailPath;
        } catch (\Exception $e) {
            // If thumbnail download fails, return null
            return null;
        }
    }

    /**
     * Delete a file.
     */
    public function deleteFile(Request $request, $id)
    {
        $file = File::findOrFail($id);
        
        // Delete physical file (works for all file types including images, PDFs, etc.)
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        
        // If it's a Vimeo video, also delete thumbnail if it exists
        if ($file->is_vimeo && $file->path) {
            $thumbnailPath = $file->path;
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
        
        // Delete database record
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
        ]);
    }

    /**
     * Delete a folder.
     */
    public function deleteFolder(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        
        // Recursively delete all files and subfolders
        $this->deleteFolderRecursive($folder);
        
        // Delete physical folder directory (if it exists)
        $folderPath = 'filemanager/' . $folder->path;
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }
        
        // Delete database record
        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Folder and all its contents deleted successfully',
        ]);
    }

    /**
     * Recursively delete folder contents (files and subfolders).
     */
    private function deleteFolderRecursive(Folder $folder)
    {
        // Delete all files in this folder (including images, PDFs, etc.)
        foreach ($folder->files as $file) {
            // Delete physical file from storage
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            // Delete database record
            $file->delete();
        }

        // Recursively delete all subfolders and their contents
        foreach ($folder->children as $child) {
            // Recursively delete child folder contents first
            $this->deleteFolderRecursive($child);
            
            // Delete physical subfolder directory
            $childPath = 'filemanager/' . $child->path;
            if (Storage::disk('public')->exists($childPath)) {
                Storage::disk('public')->deleteDirectory($childPath);
            }
            
            // Delete subfolder database record
            $child->delete();
        }
    }

    /**
     * Rename a file or folder.
     */
    public function rename(Request $request)
    {
        $request->validate([
            'type' => 'required|in:file,folder',
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        if ($request->type === 'file') {
            $item = File::findOrFail($request->id);
            $item->original_name = $request->name;
            $item->save();
        } else {
            $item = Folder::findOrFail($request->id);
            $oldPath = $item->path;
            $item->name = $request->name;
            $item->path = ($item->parent ? ($item->parent->path ? $item->parent->path . '/' : '') : '') . Str::slug($request->name);
            $item->save();

            // Rename physical folder
            if (Storage::disk('public')->exists('filemanager/' . $oldPath)) {
                Storage::disk('public')->move('filemanager/' . $oldPath, 'filemanager/' . $item->path);
            }
        }

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    /**
     * Download a file.
     */
    public function download($id)
    {
        $file = File::findOrFail($id);
        
        if (!Storage::disk('public')->exists($file->path)) {
            abort(404);
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    /**
     * Copy file or folder.
     */
    public function copy(Request $request)
    {
        $request->validate([
            'type' => 'required|in:file,folder',
            'id' => 'required|integer',
        ]);

        if ($request->type === 'file') {
            $item = File::findOrFail($request->id);
        } else {
            $item = Folder::findOrFail($request->id);
        }

        return response()->json([
            'success' => true,
            'item' => $item,
            'type' => $request->type,
        ]);
    }

    /**
     * Paste file or folder.
     */
    public function paste(Request $request)
    {
        $request->validate([
            'type' => 'required|in:file,folder',
            'id' => 'required|integer',
            'target_folder_id' => 'nullable|exists:folders,id',
            'operation' => 'required|in:copy,cut',
        ]);

        $targetFolderId = $request->input('target_folder_id', null);

        if ($request->type === 'file') {
            $file = File::findOrFail($request->id);
            
            if ($request->operation === 'copy') {
                // Copy file
                $extension = $file->extension ?? pathinfo($file->original_name, PATHINFO_EXTENSION);
                $newFileName = Str::random(40) . '.' . $extension;
                
                $targetPath = '';
                if ($targetFolderId) {
                    $targetFolder = Folder::find($targetFolderId);
                    $targetPath = $targetFolder->path ? $targetFolder->path . '/' : '';
                }
                
                $newPath = 'filemanager/' . ($targetPath ? $targetPath : '') . $newFileName;
                
                // Copy physical file
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->copy($file->path, $newPath);
                }
                
                // Create new database record
                $newFile = File::create([
                    'name' => $newFileName,
                    'original_name' => $file->original_name,
                    'path' => $newPath,
                    'mime_type' => $file->mime_type,
                    'size' => $file->size,
                    'folder_id' => $targetFolderId,
                    'extension' => $file->extension,
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File copied successfully',
                    'file' => $newFile,
                ]);
            } else {
                // Move file (cut)
                $oldPath = $file->path;
                
                $targetPath = '';
                if ($targetFolderId) {
                    $targetFolder = Folder::find($targetFolderId);
                    $targetPath = $targetFolder->path ? $targetFolder->path . '/' : '';
                }
                
                $newPath = 'filemanager/' . ($targetPath ? $targetPath : '') . $file->name;
                
                // Move physical file
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $newPath);
                }
                
                // Update database record
                $file->path = $newPath;
                $file->folder_id = $targetFolderId;
                $file->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'File moved successfully',
                    'file' => $file,
                ]);
            }
        } else {
            // Folder operations
            $folder = Folder::findOrFail($request->id);
            
            if ($request->operation === 'copy') {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder copying not yet implemented',
                ], 400);
            } else {
                // Move folder (cut)
                $folder->parent_id = $targetFolderId;
                $folder->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Folder moved successfully',
                    'folder' => $folder,
                ]);
            }
        }
    }

    /**
     * Get file or folder properties.
     */
    public function getProperties(Request $request)
    {
        $request->validate([
            'type' => 'required|in:file,folder',
            'id' => 'required|integer',
        ]);

        if ($request->type === 'file') {
            $item = File::findOrFail($request->id);
            $properties = [
                'name' => $item->original_name,
                'type' => 'File',
                'size' => $item->size,
                'formatted_size' => $item->formatted_size,
                'mime_type' => $item->mime_type,
                'extension' => $item->extension,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                'path' => $item->path,
                'url' => $item->url,
            ];
        } else {
            $item = Folder::findOrFail($request->id);
            
            // Count files and subfolders
            $fileCount = $item->files()->count();
            $folderCount = $item->children()->count();
            
            // Calculate total size
            $totalSize = $item->files()->sum('size');
            $formattedSize = $this->formatBytes($totalSize);
            
            $properties = [
                'name' => $item->name,
                'type' => 'Folder',
                'file_count' => $fileCount,
                'folder_count' => $folderCount,
                'total_size' => $totalSize,
                'formatted_size' => $formattedSize,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                'path' => $item->path,
            ];
        }

        return response()->json([
            'success' => true,
            'properties' => $properties,
        ]);
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * View file (for preview).
     * Restricted to:
     * - Admin filemanager view (all file types)
     * - Student dashboard (PDFs only)
     */
    public function view(Request $request, $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            abort(403, 'Unauthorized access');
        }

        $user = Auth::user();
        $role = null;
        if ($user->role_id) {
            $role = DB::table('roles')->where('id', $user->role_id)->first();
        }
        
        // Get role name (handle case-insensitive and null cases)
        $roleName = $role ? strtolower(trim($role->name)) : null;
        
        $file = File::findOrFail($id);
        $isPdf = $file->mime_type === 'application/pdf' || 
                 strpos($file->mime_type, 'pdf') !== false ||
                 strtolower(pathinfo($file->path, PATHINFO_EXTENSION)) === 'pdf';
        
        // Get referrer to check where the request is coming from
        $referrer = $request->header('referer') ?? $request->header('Referer') ?? '';
        $host = $request->getHost();
        $fullReferrer = $referrer ? parse_url($referrer, PHP_URL_PATH) : '';
        
        // Check if coming from filemanager (more strict check)
        $isFromFileManager = !empty($referrer) && (
            strpos($referrer, '/filemanager') !== false || 
            strpos($fullReferrer, '/filemanager') !== false ||
            strpos($referrer, route('filemanager.index', [], false)) !== false
        );
        
        // Check if coming from student dashboard (more strict check)
        $isFromStudentDashboard = !empty($referrer) && (
            strpos($referrer, '/student/dashboard') !== false || 
            strpos($fullReferrer, '/student/dashboard') !== false ||
            strpos($referrer, route('student.dashboard', [], false)) !== false
        );
        
        // Check session flags (more reliable than referrer alone)
        $hasFileManagerSession = session('filemanager_access') === true;
        $hasStudentDashboardSession = session('student_dashboard_access') === true;
        
        // Check if session is still valid (within last 4 hours for better UX with PDF.js)
        $fileManagerSessionTime = session('filemanager_access_time', 0);
        $studentDashboardSessionTime = session('student_dashboard_access_time', 0);
        $sessionValidTime = 4 * 60 * 60; // 4 hours (longer for PDF.js compatibility)
        
        $hasValidFileManagerSession = $hasFileManagerSession && (time() - $fileManagerSessionTime < $sessionValidTime);
        $hasValidStudentDashboardSession = $hasStudentDashboardSession && (time() - $studentDashboardSessionTime < $sessionValidTime);
        
        // Determine if access is allowed
        $allowed = false;
        $denialReason = '';
        
        if ($roleName === 'admin' || $roleName ==='manager') {
            // Admin: allow if coming from filemanager or has valid session
            if ($isFromFileManager || $hasValidFileManagerSession) {
                $allowed = true;
            } else {
                $denialReason = 'Admin not from filemanager and no valid session';
            }
        } elseif ($roleName === 'student') {
            // Students: only allow PDFs
            if (!$isPdf) {
                $denialReason = 'Student trying to access non-PDF file';
                Log::warning('File access denied - Non-PDF for student', [
                    'user_id' => $user->id,
                    'file_id' => $id,
                    'mime_type' => $file->mime_type,
                    'path' => $file->path
                ]);
                abort(403, 'Access denied. Students can only view PDF files from the dashboard.');
            }
            
            // Students can ONLY access PDFs if coming from dashboard
            // Direct URL access is NOT allowed - must have valid referrer from dashboard
            // Session alone is NOT enough - prevents pasting URLs directly in browser
            
            // Check if this is a direct URL access (no referrer or referrer doesn't match dashboard)
            $isDirectAccess = empty($referrer) || !$isFromStudentDashboard;
            
            if ($isDirectAccess) {
                // Direct URL access - BLOCK IT (even if session exists)
                $denialReason = 'Direct URL access blocked - referrer: ' . ($referrer ?: 'empty/missing') . ', is_from_dashboard: ' . ($isFromStudentDashboard ? 'yes' : 'no') . ', has_session: ' . ($hasStudentDashboardSession ? 'yes' : 'no');
            } elseif ($hasValidStudentDashboardSession) {
                // Coming from dashboard with valid session - ALLOW
                $allowed = true;
            } else {
                // Coming from dashboard but session expired - BLOCK (must refresh dashboard first)
                $denialReason = 'Session expired - student must visit dashboard again. Session time: ' . ($studentDashboardSessionTime > 0 ? date('Y-m-d H:i:s', $studentDashboardSessionTime) : 'never set');
            }
        } else {
            $denialReason = 'No valid role or role not admin/student';
        }
        
        if (!$allowed) {
            // Enhanced logging for debugging
            Log::warning('File access denied', [
                'user_id' => $user->id,
                'user_email' => $user->email ?? 'N/A',
                'role_id' => $user->role_id,
                'role' => $roleName ?: 'none',
                'role_object' => $role ? json_encode($role) : 'none',
                'file_id' => $id,
                'file_name' => $file->name ?? 'N/A',
                'file_mime_type' => $file->mime_type ?? 'N/A',
                'file_path' => $file->path ?? 'N/A',
                'is_pdf' => $isPdf,
                'referrer' => $referrer ?: 'none',
                'is_from_filemanager' => $isFromFileManager,
                'is_from_student_dashboard' => $isFromStudentDashboard,
                'has_filemanager_session' => $hasFileManagerSession,
                'has_student_session' => $hasStudentDashboardSession,
                'filemanager_session_valid' => $hasValidFileManagerSession,
                'student_session_valid' => $hasValidStudentDashboardSession,
                'student_session_time' => $studentDashboardSessionTime,
                'time_diff' => $studentDashboardSessionTime > 0 ? (time() - $studentDashboardSessionTime) : 'N/A',
                'denial_reason' => $denialReason,
                'auth_check' => Auth::check()
            ]);
            
            abort(403, 'Access denied. Files can only be accessed from the file manager or student dashboard.');
        }

        if (!Storage::disk('public')->exists($file->path)) {
            abort(404);
        }

        // Return file for viewing
        return Storage::disk('public')->response($file->path);
    }

    /**
     * Get authenticated Vimeo embed URL.
     */
    public function getVimeoEmbedUrl(Request $request, $id)
    {
        $file = File::findOrFail($id);
        
        if (!$file->is_vimeo || !$file->vimeo_id) {
            return response()->json([
                'success' => false,
                'message' => 'Not a Vimeo video',
            ], 400);
        }

        $accessToken = config('services.vimeo.access_token');
        
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Vimeo access token not configured',
            ], 400);
        }

        // Build embed URL exactly like the working example
        $vimeoEmbedUrl = 'https://player.vimeo.com/video/' . $file->vimeo_id;
        
        // Add parameters in the same order as working code
        $embedParams = 'autoplay=0&title=0&byline=0&portrait=0';
        
        if ($accessToken) {
            $embedParams .= '&access_token=' . urlencode($accessToken);
        }
        
        $embedUrl = $vimeoEmbedUrl . '?' . $embedParams;
        
        // Verify video exists and get embed settings
        try {
            $checkResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/vnd.vimeo.*+json;version=3.4',
            ])->get('https://api.vimeo.com/videos/' . $file->vimeo_id, [
                'fields' => 'privacy.embed,privacy.view',
            ]);

            if ($checkResponse->successful()) {
                $videoData = $checkResponse->json();
                $embedPrivacy = $videoData['privacy']['embed'] ?? 'unknown';
                $viewPrivacy = $videoData['privacy']['view'] ?? 'unknown';
                
                // For "Hide from Vimeo" videos, embed privacy MUST allow embedding
                if (in_array($embedPrivacy, ['private', 'nobody'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'EMBED SETTINGS ERROR: Your video embed privacy is "' . $embedPrivacy . '". For "Hide from Vimeo" videos to work, you MUST set "Where can this be embedded?" to "Anywhere" in Vimeo video settings. Steps: 1) Go to Vimeo.com → Your Video → Settings → Privacy tab → Scroll to "Where can this be embedded?" → Change to "Anywhere" → Save',
                        'embed_privacy' => $embedPrivacy,
                        'view_privacy' => $viewPrivacy,
                    ], 400);
                }
            }
        } catch (\Exception $e) {
            // Continue - return URL with token anyway
        }
        
        return response()->json([
            'success' => true,
            'embed_url' => $embedUrl,
            'access_token' => $accessToken, // Also return token separately for Player API
            'video_id' => $file->vimeo_id,
        ]);
    }

    /**
     * Proxy Vimeo video with authentication (for hide from vimeo videos).
     */
    public function proxyVimeoVideo($id)
    {
        $file = File::findOrFail($id);
        
        if (!$file->is_vimeo || !$file->vimeo_id) {
            abort(404);
        }

        $accessToken = config('services.vimeo.access_token');
        
        if (!$accessToken) {
            abort(404, 'Vimeo access token not configured');
        }

        // Build embed URL exactly like the working example
        $vimeoEmbedUrl = 'https://player.vimeo.com/video/' . $file->vimeo_id;
        
        // Add parameters in the same order as working code
        $embedParams = 'autoplay=0&title=0&byline=0&portrait=0';
        
        if ($accessToken) {
            $embedParams .= '&access_token=' . urlencode($accessToken);
        }
        
        $embedUrl = $vimeoEmbedUrl . '?' . $embedParams;
        
        // Return HTML page with embedded video
        return response()->view('filemanager.vimeo-embed', [
            'embedHtml' => '<iframe src="' . htmlspecialchars($embedUrl) . '" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>',
        ]);
    }
}
