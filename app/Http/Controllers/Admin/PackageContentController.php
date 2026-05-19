<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subject;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;

class PackageContentController extends Controller
{
    public function manageContent($id)
    {
        $package = SubscriptionPlan::with(['subjects', 'accessibleFolders', 'accessibleFiles'])->findOrFail($id);
        $subjects = $package->subjects;
        
        return view('admin.packages.manage-content', compact('package', 'subjects'));
    }

    public function getSubjectFolders($packageId, $subjectId)
    {
        $package = SubscriptionPlan::findOrFail($packageId);
        $subject = Subject::findOrFail($subjectId);
        
        $folders = Folder::where('subject_id', $subjectId)
            ->where('board_id', $package->board_id)
            ->where('level_id', $package->level_id)
            ->with(['children', 'files'])
            ->whereNull('parent_id')
            ->get();
            
        $accessibleFolderIds = $package->accessibleFolders()->pluck('folders.id')->toArray();
        $accessibleFileIds = $package->accessibleFiles()->pluck('files.id')->toArray();
        
        return response()->json([
            'folders' => $folders,
            'accessibleFolderIds' => $accessibleFolderIds,
            'accessibleFileIds' => $accessibleFileIds
        ]);
    }

    public function toggleFolderAccess(Request $request, $packageId)
    {
        $package = SubscriptionPlan::findOrFail($packageId);
        $folderId = $request->folder_id;
        $action = $request->action;
        
        if ($action === 'add') {
            // Add folder and all its contents
            $package->accessibleFolders()->syncWithoutDetaching([$folderId]);
            
            // Get all subfolders and files
            $folder = Folder::with('children', 'files')->find($folderId);
            $this->addFolderContentsToPackage($package, $folder);
            
        } else {
            // Remove folder access
            $package->accessibleFolders()->detach($folderId);
        }
        
        return response()->json(['success' => true]);
    }

    public function toggleFileAccess(Request $request, $packageId)
    {
        $package = SubscriptionPlan::findOrFail($packageId);
        $fileId = $request->file_id;
        
        if ($request->action === 'add') {
            $package->accessibleFiles()->syncWithoutDetaching([$fileId]);
        } else {
            $package->accessibleFiles()->detach($fileId);
        }
        
        return response()->json(['success' => true]);
    }

    public function toggleAllSubjectContent(Request $request, $packageId, $subjectId)
    {
        $package = SubscriptionPlan::findOrFail($packageId);
        
        if ($request->action === 'add') {
            // Add all folders and files for this subject
            $folders = Folder::where('subject_id', $subjectId)
                ->where('board_id', $package->board_id)
                ->where('level_id', $package->level_id)
                ->pluck('id');
                
            $files = File::where('subject_id', $subjectId)
                ->where('board_id', $package->board_id)
                ->where('level_id', $package->level_id)
                ->pluck('id');
                
            $package->accessibleFolders()->syncWithoutDetaching($folders);
            $package->accessibleFiles()->syncWithoutDetaching($files);
            
        } else {
            // Remove all folders and files for this subject
            $folderIds = Folder::where('subject_id', $subjectId)
                ->where('board_id', $package->board_id)
                ->where('level_id', $package->level_id)
                ->pluck('id');
                
            $fileIds = File::where('subject_id', $subjectId)
                ->where('board_id', $package->board_id)
                ->where('level_id', $package->level_id)
                ->pluck('id');
                
            $package->accessibleFolders()->detach($folderIds);
            $package->accessibleFiles()->detach($fileIds);
        }
        
        return response()->json(['success' => true]);
    }

    private function addFolderContentsToPackage($package, $folder)
    {
        // Add all files in this folder
        if ($folder->files->count() > 0) {
            $fileIds = $folder->files->pluck('id')->toArray();
            $package->accessibleFiles()->syncWithoutDetaching($fileIds);
        }
        
        // Recursively add subfolders and their contents
        foreach ($folder->children as $child) {
            $package->accessibleFolders()->syncWithoutDetaching([$child->id]);
            $this->addFolderContentsToPackage($package, $child);
        }
    }

    public function getFolderContents($folderId)
    {
        $folder = Folder::with(['children', 'files'])->findOrFail($folderId);
        
        return response()->json([
            'children' => $folder->children,
            'files' => $folder->files
        ]);
    }

    public function getAccessSummary($packageId)
{
    $package = SubscriptionPlan::with(['accessibleFolders', 'accessibleFiles'])->findOrFail($packageId);
    
    // Count unique accessible folders (including all subfolders)
    $accessibleFolderIds = $package->accessibleFolders->pluck('id')->toArray();
    $accessibleFileIds = $package->accessibleFiles->pluck('id')->toArray();
    
    // Count total folders and files for this package's board and level
    $totalFolders = Folder::where('board_id', $package->board_id)
        ->where('level_id', $package->level_id)
        ->count();
        
    $totalFiles = File::where('board_id', $package->board_id)
        ->where('level_id', $package->level_id)
        ->count();
        
    return response()->json([
        'accessible_folders' => count($accessibleFolderIds),
        'accessible_files' => count($accessibleFileIds),
        'total_folders' => $totalFolders,
        'total_files' => $totalFiles,
        'access_percentage' => $totalFolders > 0 ? round((count($accessibleFolderIds) / $totalFolders) * 100, 2) : 0
    ]);
}

}