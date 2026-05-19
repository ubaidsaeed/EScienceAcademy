<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\subscription;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Mail;


class StudentDashboard extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $activePaidSubscription = DB::table('subscriptions')
            ->where(['user_id' => $user->id, 'payment_status' => 'paid', 'status' => 'active'])
            ->first();

        if ($activePaidSubscription && $activePaidSubscription->end_date) {
            $currentDate = Carbon::now()->startOfDay();
            $subscriptionEndDate = Carbon::parse($activePaidSubscription->end_date)->startOfDay();

            if ($currentDate->greaterThan($subscriptionEndDate)) {
                return redirect()->route('student.subscription.expired')->with(
                    'expirePackage',
                    'Your current package has expired. Please upgrade to continue using services.'
                );
            }
        }
        // Get active subscription with all details including subjects and features
        $subscription = DB::table('subscriptions as s')
            ->select(
                's.*',
                'sp.name as plan_name',
                'b.name as board_name',
                'l.name as level_name'
            )
            ->leftJoin('subscription_plans as sp', 's.plan_id', '=', 'sp.id')
            ->leftJoin('boards as b', 's.board_id', '=', 'b.id')
            ->leftJoin('levels as l', 's.level_id', '=', 'l.id')
            ->where('s.user_id', $userId)
            ->where('s.payment_status', 'paid')
            ->where('s.status', 'active')
            ->where(function($q) {
                $q->whereNull('s.end_date')
                  ->orWhereDate('s.end_date', '>=', Carbon::today());
            })
            ->first();
          
        if (!$subscription) {
            return redirect()->route('ReNewPackage');
        }
        
        // Get subscription subjects
        $subscriptionSubjects = DB::table('subscription_subjects as ss')
            ->select('subjects.name', 'subjects.id')
            ->join('subjects', 'ss.subject_id', '=', 'subjects.id')
            ->where('ss.subscription_plan_id', $subscription->id)
            ->pluck('name', 'id')
            ->toArray();
        
        // Get subscription features
        $features = DB::table('subscription_plan_feature as spf')
            ->select('f.*')
            ->join('features as f', 'spf.feature_id', '=', 'f.id')
            ->where('spf.subscription_plan_id', $subscription->plan_id)
            ->where('f.status', 1)
            ->get();
        
        // Get hierarchical content structure based on subscription
        $hierarchy = $this->getSubscriptionBasedHierarchy($subscription, $subscriptionSubjects, $features, $userId);
        
        // Calculate progress
        $progressData = $this->calculateProgress($userId, $hierarchy);
        
        // Get progress chart data
        $progressChart = $this->getProgressChartData($userId);
        
         $startDate = Carbon::parse($subscription->start_date);
        $endDate = Carbon::parse($subscription->end_date);
        $completed_files = DB::table('completed_files')
            ->select('model_id', DB::raw('COUNT(file_id) as completed_count'))
            ->where('user_id', Auth::id())
            ->where('completed', 1)
            ->groupBy('model_id')
            ->get();
        $completedData = DB::table('completed_files')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('user_id', $user->id)
            ->where('completed', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->pluck('total', 'date');
            $allDates = collect();
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $allDates->push($current->toDateString());
            $current->addDay();
        }
        
        $progress = $allDates->map(function ($date) use ($completedData) {
            return [
                'date' => $date,
                'total' => $completedData->get($date, 0),
            ];
        });
        
        $currentDate = Carbon::now();
        $totalDuration = $startDate->diffInDays($endDate);
        $completedDuration = $startDate->diffInDays($currentDate);
        $completionPercentage = ($totalDuration > 0) ? ($completedDuration / $totalDuration) * 100 : 100;
        $completionPercentage = min(100, max(0, round($completionPercentage)));
        $completed_lassen = DB::table('completed_files')
            ->where('user_id', Auth::id())
            ->where('completed', 1)
            ->get();

        $completed_files = $completed_files;
        $completed_lectures = $completed_lassen->count();
        
        // Debug
        Log::info('Subscription Data:', [
            'completed_lectures' =>$completed_lectures,
            'progress' =>$progress,
            'completed_files' =>$completed_files,
            'subscription_id' => $subscription->id,
            'board' => $subscription->board_name,
            'level' => $subscription->level_name,
            'subjects' => $subscriptionSubjects,
            'features' => $features->pluck('name')->toArray(),
            'hierarchy_count' => count($hierarchy)
        ]);
        // dd('completed_lectures' ,$completed_lectures,
        //     'progress' ,$progress,
        //     'completed_files' ,$completed_files,
        //     'subscription_id' , $subscription->id,
        //     'board' , $subscription->board_name,
        //     'level' , $subscription->level_name,
        //     'subjects' , $subscriptionSubjects,
        //     'features' , $features->pluck('name')->toArray(),
        //     'hierarchy_count' , count($hierarchy));
        
        // Set session flag to allow PDF access from student dashboard
        session(['student_dashboard_access' => true, 'student_dashboard_access_time' => time()]);
        
        return view('student.dashboard', [
            'progress' =>$progress,
            'subscription' => $subscription,
            'completed_lectures' =>$completed_lectures,
            'completed_files' => $completed_files,
            'features' => $features,
            'hierarchy' => $hierarchy,
            'subscriptionSubjects' => $subscriptionSubjects,
            'completionPercentage' => $completionPercentage,
            'completedLectures' => $progressData['completedCount'],
            'totalLectures' => $progressData['totalCount'],
            'progressChart' => $progressChart
        ]);
    }
    
    /**
     * Get hierarchy based on subscription subjects and features
     */
    private function getSubscriptionBasedHierarchy($subscription, $subscriptionSubjects, $features, $userId)
    {
        // Step 1: Get folders that match subscription board
        $boardFolders = DB::table('folders')
            ->where(function($query) use ($subscription) {
                // Match by board name
                if ($subscription->board_name) {
                    $query->where('name', 'like', '%' . $subscription->board_name . '%');
                }
                // Match by board_id
                if ($subscription->board_id) {
                    $query->orWhere('board_id', $subscription->board_id);
                }
            })
            ->where(function($query) {
                $query->whereNull('parent_id')
                      ->orWhere('parent_id', 0);
            })
            ->get();
        
        // If no specific board folders found, get all root folders
        if ($boardFolders->isEmpty()) {
            $boardFolders = DB::table('folders')
                ->whereNull('parent_id')
                ->orWhere('parent_id', 0)
                ->orderBy('name')
                ->get();
        }
        
        // Step 2: Build hierarchy with subscription filtering
        $hierarchy = [];
        foreach ($boardFolders as $boardFolder) {
            $boardNode = $this->buildSubscriptionFilteredNode(
                $boardFolder, 
                $subscription, 
                $subscriptionSubjects, 
                $features, 
                $userId, 
                0
            );
            
            if ($boardNode && $this->shouldIncludeNode($boardNode, $subscriptionSubjects, $features)) {
                $hierarchy[] = $boardNode;
            }
        }
        
        return $hierarchy;
    }
    
    /**
     * Build node with subscription filtering
     */
    private function buildSubscriptionFilteredNode($folder, $subscription, $subscriptionSubjects, $features, $userId, $depth)
    {
        $type = $this->getTypeByDepth($depth);
        
        $node = [
            'id' => $folder->id,
            'name' => $folder->name,
            'type' => $type,
            'children' => [],
            'files' => []
        ];
        
        // Get child folders
        $childFolders = DB::table('folders')
            ->where('parent_id', $folder->id)
            ->orderBy('name')
            ->get();
        
        // Filter children based on depth and subscription
        foreach ($childFolders as $childFolder) {
            $childNode = $this->buildSubscriptionFilteredNode(
                $childFolder,
                $subscription,
                $subscriptionSubjects,
                $features,
                $userId,
                $depth + 1
            );
            
            // Only include child if it matches subscription criteria
            if ($childNode && $this->shouldIncludeChildNode($childNode, $depth + 1, $subscription, $subscriptionSubjects, $features)) {
                $node['children'][] = $childNode;
            }
        }
        
        // Get files for this folder
        $node['files'] = $this->getFilesWithProgress($folder->id, $userId);
        
        return $node;
    }
    
    /**
     * Check if node should be included based on subscription
     */
    private function shouldIncludeNode($node, $subscriptionSubjects, $features)
    {
        // Always include board level (depth 0)
        if ($node['type'] === 'board') {
            return true;
        }
        
        // For subject level - check if subject is in subscription
        if ($node['type'] === 'subject') {
            foreach ($subscriptionSubjects as $subjectId => $subjectName) {
                if (stripos($node['name'], $subjectName) !== false) {
                    return true;
                }
            }
            return false;
        }
        
        // For feature level - check if feature is in subscription
        if ($node['type'] === 'feature') {
            foreach ($features as $feature) {
                if (stripos($node['name'], $feature->name) !== false) {
                    return true;
                }
            }
            return false;
        }
        
        // For content level - include if it has children or files
        return !empty($node['children']) || !empty($node['files']);
    }
    
    /**
     * Check if child node should be included
     */
    private function shouldIncludeChildNode($childNode, $depth, $subscription, $subscriptionSubjects, $features)
    {
        $type = $this->getTypeByDepth($depth);
        
        switch ($type) {
            case 'level':
                // Filter by level
                if ($subscription->level_name) {
                    return stripos($childNode['name'], $subscription->level_name) !== false;
                }
                return true;
                
            case 'subject':
                // Filter by subscription subjects
                foreach ($subscriptionSubjects as $subjectId => $subjectName) {
                    if (stripos($childNode['name'], $subjectName) !== false) {
                        return true;
                    }
                }
                return false;
                
            case 'feature':
                // Filter by subscription features
                foreach ($features as $feature) {
                    if (stripos($childNode['name'], $feature->name) !== false) {
                        return true;
                    }
                }
                return false;
                
            default:
                return true;
        }
    }
    
    /**
     * Get type by depth
     */
    private function getTypeByDepth($depth)
    {
        $types = ['board', 'level', 'subject', 'feature', 'content'];
        return $types[min($depth, count($types) - 1)];
    }
    
    /**
     * ALTERNATIVE SIMPLE METHOD - Filter by subscription directly in query
     */
    private function getDirectSubscriptionHierarchy($subscription, $subscriptionSubjects, $features, $userId)
    {
        // Get subject IDs from subscription
        $subjectIds = array_keys($subscriptionSubjects);
        
        // Get feature names from subscription
        $featureNames = $features->pluck('name')->toArray();
        
        // Get all folders that match subscription criteria
        $folders = DB::table('folders')
            ->where(function($query) use ($subscription, $subjectIds, $featureNames) {
                // Board level - match board
                if ($subscription->board_name) {
                    $query->where('name', 'like', '%' . $subscription->board_name . '%');
                }
                
                // Subject level - match subscription subjects
                if (!empty($subjectIds)) {
                    foreach ($subscriptionSubjects as $subjectName) {
                        $query->orWhere('name', 'like', '%' . $subjectName . '%');
                    }
                }
                
                // Feature level - match subscription features
                if (!empty($featureNames)) {
                    foreach ($featureNames as $featureName) {
                        $query->orWhere('name', 'like', '%' . $featureName . '%');
                    }
                }
            })
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();
        
        // If no specific folders found, get empty array
        if ($folders->isEmpty()) {
            return [];
        }
        
        // Build tree from matching folders
        return $this->buildTreeFromFolders($folders, $userId);
    }
    
    /**
     * Build tree from filtered folders
     */
    // private function buildTreeFromFolders($folders, $userId)
    // {
    //     // Get root folders
    //     $rootFolders = $folders->filter(function($folder) use ($folders) {
    //         return empty($folder->parent_id) || 
    //               $folder->parent_id == 0 ||
    //               !$folders->contains('id', $folder->parent_id);
    //     });
        
    //     // Build tree
    //     $tree = $rootFolders->map(function($folder) use ($folders, $userId) {
    //         return $this->buildTreeNode($folder, $folders, $userId, 0);
    //     });
        
    //     return $tree->toArray();
    // }
    
    /**
     * Build tree node recursively
     */
    private function buildTreeNode($folder, $folders, $userId, $depth)
    {
        $node = [
            'id' => $folder->id,
            'name' => $folder->name,
            'type' => $this->getTypeByDepth($depth),
            'children' => [],
            'files' => $this->getFilesWithProgress($folder->id, $userId)
        ];
        
        // Get children
        $children = $folders->where('parent_id', $folder->id);
        
        foreach ($children as $child) {
            $node['children'][] = $this->buildTreeNode($child, $folders, $userId, $depth + 1);
        }
        
        return $node;
    }
    
    /**
     * Get files with user progress
     */
    // private function getFilesWithProgress($folderId, $userId)
    // {
    //     $files = DB::table('files')
    //         ->where('folder_id', $folderId)
    //         ->orderBy('name')
    //         ->get()
    //         ->map(function($file) use ($userId) {
    //             $completed = DB::table('user_progress')
    //                 ->where('media_id', $file->id)
    //                 ->where('user_id', $userId)
    //                 ->exists();
                
    //             $extension = $file->extension ?? pathinfo($file->name, PATHINFO_EXTENSION);
                
    //             return [
    //                 'id' => $file->id,
    //                 'name' => $file->name,
    //                 'original_name' => $file->original_name ?? $file->name,
    //                 'path' => $file->path,
    //                 'mime_type' => $file->mime_type ?? $this->getMimeTypeByExtension($extension),
    //                 'extension' => $extension,
    //                 'size' => $file->size ?? 0,
    //                 'is_vimeo' => $file->is_vimeo ?? false,
    //                 'vimeo_id' => $file->vimeo_id,
    //                 'vimeo_url' => $file->vimeo_url,
    //                 'custom_properties' => json_decode($file->custom_properties ?? '{}', true),
    //                 'is_completed' => $completed,
    //                 'created_at' => $file->created_at
    //             ];
    //         })
    //         ->toArray();
        
    //     return $files;
    // }
    private function getFilesWithProgress($folderId, $userId)
{
    $files = DB::table('files')
        ->where('folder_id', $folderId)
         ->orderBy('name', 'desc')
        ->get()
        ->map(function($file) use ($userId) {
            // Check completed_files table for completion status
            $completedRecord = DB::table('completed_files')
                ->where('file_id', $file->id)
                ->where('user_id', $userId)
                ->where('model_id', $file->folder_id)
                ->first();
            
            // Check if completed column is 1
            $isCompleted = $completedRecord && $completedRecord->completed == 1;
            
            $extension = $file->extension ?? pathinfo($file->name, PATHINFO_EXTENSION);
            
            return [
                'id' => $file->id,
                'name' => $file->name,
                'original_name' => $file->original_name ?? $file->name,
                'path' => $file->path,
                'mime_type' => $file->mime_type ?? $this->getMimeTypeByExtension($extension),
                'extension' => $extension,
                'size' => $file->size ?? 0,
                'is_vimeo' => $file->is_vimeo ?? false,
                'vimeo_id' => $file->vimeo_id,
                'vimeo_url' => $file->vimeo_url,
                'custom_properties' => json_decode($file->custom_properties ?? '{}', true),
                'is_completed' => $isCompleted ? 1 : 0, // Return 1 or 0
                'created_at' => $file->created_at
            ];
        })
         ->sortBy('order')
            ->values()
        ->toArray();
    
    return $files;
}
    
    /**
     * Get MIME type by file extension
     */
    private function getMimeTypeByExtension($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
        ];
        
        $ext = strtolower($extension);
        return $mimeTypes[$ext] ?? 'application/octet-stream';
    }
    
    /**
     * Calculate progress
     */
    private function calculateProgress($userId, $hierarchy)
    {
        $completedCount = 0;
        $totalCount = 0;
        
        $countFiles = function($node) use (&$completedCount, &$totalCount, &$countFiles) {
            if (isset($node['files']) && is_array($node['files'])) {
                $totalCount += count($node['files']);
                foreach ($node['files'] as $file) {
                    if ($file['is_completed']) {
                        $completedCount++;
                    }
                }
            }
            
            if (isset($node['children']) && is_array($node['children'])) {
                foreach ($node['children'] as $child) {
                    $countFiles($child);
                }
            }
        };
        
        if (is_array($hierarchy)) {
            foreach ($hierarchy as $node) {
                $countFiles($node);
            }
        }
        
        $completionPercentage = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
        
        return [
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'completionPercentage' => round($completionPercentage, 1)
        ];
    }
    
    /**
     * Get progress chart data
     */
    private function getProgressChartData($userId)
    {
        $progress = DB::table('user_progress')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'dates' => $progress->pluck('date')->map(function($date) {
                return date('M d', strtotime($date));
            }),
            'counts' => $progress->pluck('count')
        ];
    }
    
    /**
     * TEST METHOD - For debugging subscription filtering
     */
    private function testSubscriptionFiltering($subscription, $subscriptionSubjects, $features, $userId)
    {
        Log::info('=== SUBSCRIPTION FILTERING TEST ===');
        Log::info('Board: ' . $subscription->board_name);
        Log::info('Level: ' . $subscription->level_name);
        Log::info('Subjects: ' . implode(', ', $subscriptionSubjects));
        Log::info('Features: ' . $features->pluck('name')->implode(', '));
        
        // Test 1: Find board folders
        $boardFolders = DB::table('folders')
            ->where('name', 'like', '%' . $subscription->board_name . '%')
            ->where(function($q) {
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->get();
        
        Log::info('Board folders found: ' . $boardFolders->count());
        
        // Test 2: Find subject folders
        $subjectFolders = collect();
        foreach ($subscriptionSubjects as $subjectName) {
            $found = DB::table('folders')
                ->where('name', 'like', '%' . $subjectName . '%')
                ->get();
            $subjectFolders = $subjectFolders->merge($found);
        }
        
        Log::info('Subject folders found: ' . $subjectFolders->count());
        
        // Test 3: Find feature folders
        $featureFolders = collect();
        foreach ($features as $feature) {
            $found = DB::table('folders')
                ->where('name', 'like', '%' . $feature->name . '%')
                ->get();
            $featureFolders = $featureFolders->merge($found);
        }
        
        Log::info('Feature folders found: ' . $featureFolders->count());
        
        return [
            'board_folders' => $boardFolders,
            'subject_folders' => $subjectFolders,
            'feature_folders' => $featureFolders
        ];
    }
    
    // ... rest of your methods (getFolderMedia, markMediaCompleted, etc.) remain the same ...

    
    /**
     * Get folder media (AJAX)
     */
    public function getFolderMedia(Request $request, $folderId)
    {
        try {
            $user = Auth::user();
            
            // Get files for the folder
            $files = $this->getFilesWithProgress($folderId, $user->id);
            
            return response()->json([
                'success' => true,
                'folder_id' => $folderId,
                'files' => $files,
                'total' => count($files),
                'completed' => collect($files)->where('is_completed', true)->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Get folder media error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load media'
            ], 500);
        }
    }
    
    /**
     * Mark media as completed (AJAX)
     */
    // public function markMediaCompleted(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'file_id' => 'required|integer',
    //             'folder_id' => 'required|integer'
    //         ]);
            
    //         $user = Auth::user();
            
    //         // Check if already completed
    //         $existing = DB::table('user_progress')
    //             ->where('user_id', $user->id)
    //             ->where('media_id', $request->file_id)
    //             ->first();
            
    //         if (!$existing) {
    //             DB::table('user_progress')->insert([
    //                 'user_id' => $user->id,
    //                 'media_id' => $request->file_id,
    //                 'folder_id' => $request->folder_id,
    //                 'completed_at' => now(),
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }
            
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Media marked as completed'
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Mark media completed error: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to mark media as completed'
    //         ], 500);
    //     }
    // }
    
    /**
     * Toggle folder state (AJAX)
     */
    public function toggleFolder(Request $request, $folderId)
    {
        try {
            $openFolders = session('open_folders', []);
            $isOpen = filter_var($request->is_open, FILTER_VALIDATE_BOOLEAN);
            
            if ($isOpen) {
                if (!in_array($folderId, $openFolders)) {
                    $openFolders[] = $folderId;
                }
            } else {
                $openFolders = array_filter($openFolders, function($id) use ($folderId) {
                    return $id != $folderId;
                });
            }
            
            session(['open_folders' => $openFolders]);
            
            return response()->json([
                'success' => true,
                'message' => 'Folder state updated',
                'folder_id' => $folderId,
                'is_open' => $isOpen
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle folder error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update folder state'
            ], 500);
        }
    }
    
    /**
     * Restart folder progress (AJAX)
     */
    public function restartFolder(Request $request, $folderId)
    {
        try {
            $user = Auth::user();
            
            // Get all files in this folder
            $fileIds = DB::table('files')
                ->where('folder_id', $folderId)
                ->pluck('id');
            
            // Delete progress for these files
            DB::table('user_progress')
                ->where('user_id', $user->id)
                ->whereIn('media_id', $fileIds)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Folder progress reset',
                'folder_id' => $folderId,
                'completed_count' => 0,
                'total_files' => count($fileIds)
            ]);
        } catch (\Exception $e) {
            Log::error('Restart folder error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset folder progress'
            ], 500);
        }
    }
    
    /**
     * Cancel plan
     */
     public function cancelPlan()
{
    try {
        $user = Auth::user();

        $subscription = DB::table('subscriptions')
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        DB::beginTransaction();

        DB::table('subscriptions')
            ->where('id', $subscription->id)
            ->update([
                'status' => 'cancelled',
                'updated_at' => now()
            ]);

        $plan = DB::table('subscription_plans')
            ->where('id', $subscription->plan_id)
            ->first();

        $token = Str::random(64);

        Mail::send(
            'emails.subscriptionCancelledEmail',
            compact('user', 'token', 'plan', 'subscription'),
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Subscription Cancelled Confirmation');
            }
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully',
            'redirect' => route('student.subscription.cancelled')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Cancel plan error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to cancel plan'
        ], 500);
    }
}
    // public function cancelPlan()
    // {
    //     try {
    //         $user = Auth::user();
    //         $subscription = DB::table('subscriptions')
    //         ->where(['user_id' =>  $user->id, 'payment_status' => 'paid', 'status' => 'active'])
    //         ->first();
    //         DB::table('subscriptions')
    //             ->where('user_id', $user->id)
    //             ->where('status', 'active')
    //             ->update([
    //                 'status' => 'cancelled',
    //                 'updated_at' => now()
    //             ]);
    //             $plan = DB::table('subscription_plans')
    //         ->where('id',$subscription->plan_id)
    //         ->first();
    //          $token = Str::random(64);
    //         FacadesMail::send('emails.subscriptionCancelledEmail', ['user' => $user,'token'=>$token,'plan' =>$plan,'subscription' =>$subscription], function($message) use($user){
    //         $message->to($user->email);
    //         $message->subject('Subscription Cancelled Confirmation');
    //         });
    //         return redirect()->route('student.dashboard')
    //             ->with('cancelMessage', true);
    //     } catch (\Exception $e) {
    //         Log::error('Cancel plan error: ' . $e->getMessage());
    //         return redirect()->route('student.dashboard')
    //             ->with('error', 'Failed to cancel plan');
    //     }
    // }
    // public function cancelPlan()
    // {
    //     $user_id = Auth::id();
    //     $user = User::find($user_id);
        
    //     // Fetch the user's subscription
    //     $subscription = DB::table('subscriptions')
    //         ->where(['user_id' =>  $user->id, 'payment_status' => 'paid', 'status' => 'active'])
    //         ->first();
            
        // if (!$subscription) {
        //     // Handle case where no subscription is found
        //   return response()->json('cancelMessage', false);
        // } elseif ($subscription->user_id != $user->id) {
        //   return response()->json('cancelMessage', false);
        // } else {
           
            // DB::table('subscriptions')
            //     ->where('user_id', $user->id)
            //     ->where('status', 'active')
            //     ->update([
            //         'status' => 'cancelled',
            //         'updated_at' => now()
            //     ]);
        //     $plan = DB::table('subscription_plans')
        //     ->where('id',$subscription->plan_id)
        //     ->first();
        //      $token = Str::random(64);
        //     FacadesMail::send('emails.subscriptionCancelledEmail', ['user' => $user,'token'=>$token,'plan' =>$plan,'subscription' =>$subscription], function($message) use($user){
        //     $message->to($user->email);
        //     $message->subject('Subscription Cancelled Confirmation');
        // });
                // return response()->json('cancelMessage', true);
        // }
    // }
    
    
    /**
 * Direct SQL approach with subscription filtering
 */
// private function getDirectSubscriptionHierarchy($subscription, $userId)
// {
//     // Get subscribed subject names
//     $subjectNames = $subscription->subjects ? explode(',', $subscription->subjects) : [];
//     $cleanSubjectNames = array_map(function($name) {
//         return trim(strtolower($name));
//     }, $subjectNames);
    
//     // Build subject LIKE conditions
//     $subjectConditions = [];
//     foreach ($cleanSubjectNames as $subject) {
//         $subjectConditions[] = "LOWER(f.name) LIKE '%{$subject}%'";
//     }
//     $subjectCondition = !empty($subjectConditions) ? 'AND (' . implode(' OR ', $subjectConditions) . ')' : '';
    
//     // Query to get folders with subscription filtering
//     $folders = DB::select("
//         WITH RECURSIVE folder_tree AS (
//             -- Start with root folders matching board
//             SELECT 
//                 f1.id,
//                 f1.name,
//                 f1.parent_id,
//                 0 as depth
//             FROM folders f1
//             WHERE (f1.parent_id IS NULL OR f1.parent_id = 0)
//             AND (
//                 LOWER(f1.name) LIKE '%" . strtolower($subscription->board_name) . "%'
//                 OR f1.board_id = ?
//             )
            
//             UNION ALL
            
//             -- Recursively get children with filtering
//             SELECT 
//                 f2.id,
//                 f2.name,
//                 f2.parent_id,
//                 ft.depth + 1 as depth
//             FROM folders f2
//             INNER JOIN folder_tree ft ON f2.parent_id = ft.id
//             WHERE 
//                 -- At depth 0 (board), include all children
//                 (ft.depth = 0) 
//                 OR 
//                 -- At depth 1 (level), filter by level
//                 (ft.depth = 1 AND (
//                     LOWER(f2.name) LIKE '%" . strtolower($subscription->level_name) . "%'
//                     OR f2.level_id = ?
//                 ))
//                 OR 
//                 -- At depth 2 (subject), filter by subscribed subjects
//                 (ft.depth = 2 {$subjectCondition})
//                 OR 
//                 -- At depth 3+ (feature/content), include all if parent was included
//                 (ft.depth >= 3)
//         )
//         SELECT * FROM folder_tree
//         ORDER BY parent_id, name
//     ", [$subscription->board_id, $subscription->level_id]);
    
//     // Convert to collection
//     $folders = collect($folders);
    
//     // Build tree structure
//     return $this->buildTreeFromFolders($folders, $userId);
// }

/**
 * Build tree from filtered folders
 */
private function buildTreeFromFolders($folders, $userId)
{
    // Group by parent_id for easier tree building
    $foldersByParent = $folders->groupBy('parent_id');
    
    // Get root folders (parent_id = 0 or NULL)
    $roots = $folders->where('parent_id', 0)
                     ->merge($folders->whereNull('parent_id'));
    
    return $roots->map(function($folder) use ($foldersByParent, $userId) {
        return $this->buildTreeRecursive($folder, $foldersByParent, $userId, 0);
    })->toArray();
}

/**
 * Recursive tree building
 */
private function buildTreeRecursive($folder, $foldersByParent, $userId, $depth)
{
    $node = [
        'id' => $folder->id,
        'name' => $folder->name,
        'type' => $this->getTypeByDepth($depth),
        'children' => [],
        'files' => []
    ];
    
    // Get children
    $children = $foldersByParent->get($folder->id, collect());
    
    foreach ($children as $child) {
        $node['children'][] = $this->buildTreeRecursive($child, $foldersByParent, $userId, $depth + 1);
    }
    
    // Get files
    $node['files'] = $this->getFilesWithProgress($folder->id, $userId);
    
    return $node;
}
/**
 * Modified getDirectHierarchy with subscription filtering
 */
private function getSubscriptionFilteredHierarchy($subscription, $userId)
{
    // Get subscribed subject names
    $subjectNames = $subscription->subjects ? explode(',', $subscription->subjects) : [];
    $cleanSubjectNames = array_map(function($name) {
        return trim(strtolower($name));
    }, $subjectNames);
    
    // Get all folders
    $folders = DB::table('folders as f')
        ->select(
            'f.id',
            'f.name',
            'f.parent_id'
        )
        ->orderBy('f.parent_id')
        ->orderBy('f.name')
        ->get();
    
    // Build tree but filter at subject level
    $tree = [];
    foreach ($folders as $folder) {
        if (empty($folder->parent_id) || $folder->parent_id == 0) {
            $boardNode = $this->buildFilteredTree($folder, $folders, $subscription, $cleanSubjectNames, $userId, 0);
            if ($boardNode) {
                $tree[] = $boardNode;
            }
        }
    }
    
    return $tree;
}

/**
 * Build filtered tree
 */
private function buildFilteredTree($folder, $folders, $subscription, $subscribedSubjects, $userId, $depth)
{
    $type = $this->getTypeByDepth($depth);
    
    // Check if folder should be included at this depth
    if ($depth == 2) { // Subject level
        $folderName = strtolower($folder->name);
        $shouldInclude = false;
        
        foreach ($subscribedSubjects as $subject) {
            if (str_contains($folderName, $subject)) {
                $shouldInclude = true;
                break;
            }
        }
        
        if (!$shouldInclude) {
            return null;
        }
    }
    
    $node = [
        'id' => $folder->id,
        'name' => $folder->name,
        'type' => $type,
        'files' => $this->getFilesWithProgress($folder->id, $userId),
        'children' => []
    ];
    
    // Get children
    $childFolders = $folders->where('parent_id', $folder->id);
    
    foreach ($childFolders as $child) {
        $childNode = $this->buildFilteredTree($child, $folders, $subscription, $subscribedSubjects, $userId, $depth + 1);
        if ($childNode) {
            $node['children'][] = $childNode;
        }
    }
    
    return $node;
}
    // ... other methods remain the same ...

    private function getDirectHierarchy($userId)
{
    // Get all folders with their files
    $folders = DB::table('folders as f')
        ->leftJoin('files as fl', 'f.id', '=', 'fl.folder_id')
        ->select(
            'f.id',
            'f.name',
            'f.parent_id',
            DB::raw('COUNT(fl.id) as file_count')
        )
        ->groupBy('f.id', 'f.name', 'f.parent_id')
        ->orderBy('f.parent_id')
        ->orderBy('f.name')
        ->get();
    
    // Build tree
    $tree = [];
    foreach ($folders as $folder) {
        if (empty($folder->parent_id) || $folder->parent_id == 0) {
            $tree[$folder->id] = [
                'id' => $folder->id,
                'name' => $folder->name,
                'type' => 'board',
                'file_count' => $folder->file_count,
                'children' => $this->getFolderChildren($folder->id, $folders, $userId)
            ];
        }
    }
    
    return array_values($tree);
}

private function getFolderChildren($parentId, $folders, $userId, $depth = 1)
{
    $children = [];
    $childFolders = $folders->where('parent_id', $parentId);
    
    foreach ($childFolders as $folder) {
        $type = $this->getTypeByDepth($depth);
        
        $node = [
            'id' => $folder->id,
            'name' => $folder->name,
            'type' => $type,
            'file_count' => $folder->file_count,
            'files' => $this->getFilesWithProgress($folder->id, $userId),
            'children' => $this->getFolderChildren($folder->id, $folders, $userId, $depth + 1)
        ];
        
        $children[] = $node;
    }
    
    return $children;
}

// private function getTypeByDepth($depth)
// {
//     $types = ['board', 'level', 'subject', 'feature', 'content'];
//     return $types[min($depth, count($types) - 1)];
// }
/**
 * Get updated stats (AJAX)
 */
/**
 * Get updated stats (AJAX) - Simplified version
 */
/**
 * Get updated stats (AJAX) - Direct database query version
 */
public function getStats(Request $request)
{
    try {
        $user = Auth::user();
        $userId = $user->id;
        
        // Get subscription
        $subscription = DB::table('subscriptions as s')
            ->select(
                's.*',
                'sp.name as plan_name',
                'b.name as board_name',
                'l.name as level_name'
            )
            ->leftJoin('subscription_plans as sp', 's.plan_id', '=', 'sp.id')
            ->leftJoin('boards as b', 's.board_id', '=', 'b.id')
            ->leftJoin('levels as l', 's.level_id', '=', 'l.id')
            ->where('s.user_id', $userId)
            ->where('s.payment_status', 'paid')
            ->where('s.status', 'active')
            ->where(function($query) {
                $query->whereNull('s.end_date')
                      ->orWhereDate('s.end_date', '>=', Carbon::today());
            })
            ->first();
        
        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription'
            ]);
        }
        
        $startDate = Carbon::parse($subscription->start_date);
        $endDate = Carbon::parse($subscription->end_date);

        // Get completed files count
        $completedData = DB::table('completed_files')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('user_id', $user->id)
            ->where('completed', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->pluck('total', 'date');

        $allDates = collect();
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $allDates->push($current->toDateString());
            $current->addDay();
        }

        $progress = $allDates->map(function ($date) use ($completedData) {
            return [
                'date' => $date,
                'total' => $completedData->get($date, 0),
            ];
        });

        $currentDate = Carbon::now();
        $totalDuration = $startDate->diffInDays($endDate);
        $completedDuration = $startDate->diffInDays($currentDate);
        $completionPercentage = ($totalDuration > 0) ? ($completedDuration / $totalDuration) * 100 : 100;
        $completionPercentage = min(100, max(0, round($completionPercentage)));

        $completed_lassen = DB::table('completed_files')
            ->where('user_id', Auth::id())
            ->where('completed', 1)
            ->get();
            
        // ADD THIS: Get total lectures count
        // Estimate total lectures based on subscription duration or get from database
        $totalLectures = 0;
        
        // Option 1: Estimate based on average daily progress
        if ($completedDuration > 0 && $completed_lassen->count() > 0) {
            $averageDailyProgress = $completed_lassen->count() / $completedDuration;
            $totalLectures = round($averageDailyProgress * $totalDuration);
        }
        
        // Option 2: Get from files TABLE (if you have a way to relate files to subscription)
        // $totalLectures = DB::table('files')
        //     ->join('folders', 'files.folder_id', '=', 'folders.id')
        //     ->where('folders.board_id', $subscription->board_id)
        //     ->where('folders.level_id', $subscription->level_id)
        //     ->count();
        
        // Option 3: Use a fixed estimate if database query fails
        if ($totalLectures <= 0) {
            $totalLectures = 50; // Default estimate
        }
            
        return response()->json([ 
            'success' => true,
            'progress' => $progress,
            'completed_lectures' => $completed_lassen->count(),
            'total_lectures' => $totalLectures, // ADD THIS
            'completion_percentage' => round($completionPercentage, 1),
            'subscription_progress' => $completionPercentage // Use completionPercentage instead of completedDuration
        ]);
        
    } catch (\Exception $e) {
        // Log the error
        error_log('Get stats error: ' . $e->getMessage());
        error_log('Trace: ' . $e->getTraceAsString());
        
        // Return a basic error response
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching stats',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }
}

/**
 * Helper method to count folders in hierarchy
 */
private function countFoldersInHierarchy($hierarchy)
{
    $count = 0;
    
    $countFolders = function($node) use (&$count, &$countFolders) {
        $count++;
        
        if (isset($node['children']) && is_array($node['children'])) {
            foreach ($node['children'] as $child) {
                $countFolders($child);
            }
        }
    };
    
    if (is_array($hierarchy)) {
        foreach ($hierarchy as $node) {
            $countFolders($node);
        }
    }
    
    return $count;
}

/**
 * Get recent activity (AJAX)
 */
public function getRecentActivity(Request $request)
{
    try {
        $user = Auth::user();
        $userId = $user->id;
        
        // Get recent progress entries
        $activities = DB::table('user_progress as up')
            ->select(
                'up.*',
                'f.name as file_name',
                'f.mime_type',
                'fl.name as folder_name'
            )
            ->leftJoin('files as f', 'up.media_id', '=', 'f.id')
            ->leftJoin('folders as fl', 'up.folder_id', '=', 'fl.id')
            ->where('up.user_id', $userId)
            ->orderBy('up.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($activity) {
                return [
                    'type' => 'completed',
                    'message' => 'Completed ' . $activity->file_name,
                    'created_at' => $activity->created_at,
                    'folder_name' => $activity->folder_name
                ];
            });
        
        // If no progress entries, get subscription activities
        if ($activities->isEmpty()) {
            $activities = DB::table('subscriptions')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($subscription) {
                    return [
                        'type' => 'subscription',
                        'message' => 'Subscribed to ' . $subscription->plan_name,
                        'created_at' => $subscription->created_at
                    ];
                });
        }
        
        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
        
    } catch (\Exception $e) {
        Log::error('Get recent activity error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'activities' => []
        ]);
    }
}

/**
 * Enhanced markMediaCompleted to include activity logging
 */
public function markMediaCompleted(Request $request)
{
    try {
        $request->validate([
            'file_id' => 'required|integer',
            'folder_id' => 'required|integer'
        ]);
        
        $user = Auth::user();
        
        // Check if already completed
        $existing = DB::table('user_progress')
            ->where('user_id', $user->id)
            ->where('media_id', $request->file_id)
            ->first();
        
        if (!$existing) {
            DB::table('user_progress')->insert([
                'user_id' => $user->id,
                'media_id' => $request->file_id,
                'folder_id' => $request->folder_id,
                'completed_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Log activity
            $this->logActivity($user->id, 'completed', [
                'file_id' => $request->file_id,
                'folder_id' => $request->folder_id
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Media marked as completed'
        ]);
    } catch (\Exception $e) {
        Log::error('Mark media completed error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark media as completed'
        ], 500);
    }
}

/**
 * Log activity for user
 */
private function logActivity($userId, $type, $data = [])
{
    try {
        DB::table('user_activities')->insert([
            'user_id' => $userId,
            'type' => $type,
            'data' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    } catch (\Exception $e) {
        Log::error('Log activity error: ' . $e->getMessage());
    }
}

   public function completedSlide(Request $request)
    {


        $user_id = Auth::user()->id;
        $completed = DB::table('completed_files')
            ->where('user_id', $user_id)
            ->where('file_id', $request->file_id)
            ->where('model_id', $request->model_id)
            ->first();
        if ($completed) {
            DB::table('completed_files')
                ->where('user_id', $user_id)
                ->where('file_id', $request->file_id)
                ->where('model_id', $request->model_id)
                ->update([
                    'user_id' => $user_id,
                    'model_id' => $request->model_id,
                    'file_id' => $request->file_id,
                    'updated_at' => now(),
                    'created_at' => now(),
                    'completed' => 1
                ]);
        } else {
            DB::table('completed_files')->insert([
                'user_id' => $user_id,
                'model_id' => $request->model_id,
                'file_id' => $request->file_id,
                'created_at' => now(),
                'completed' => 1
            ]);
        }
        $userId = Auth::id();

        // Get completed file IDs
        $completedFiles = DB::table('completed_files')
            ->where('model_id', $request->model_id)
            ->where('user_id', $user_id)
            ->where('completed', 1)
            ->pluck('file_id')
            ->toArray();

        // Fetch next media records that haven't been viewed
        $limit = 1; // Define the limit properly
        // return $this->response()->json($request);
        $mediaRecords = DB::table('media')
            ->where('model_id', $request->model_id)
            // ->whereNotIn('id', $completedFiles)
            ->where('id', '>', $request->file_id)
            ->limit($limit)
            ->get()
            ->map(function ($media) {
                $folder = \LivewireFilemanager\Filemanager\Models\Folder::find($media->model_id);
                if ($folder) {
                    $folderPath = buildFolderPath($folder->id);
                    $media->path = url("filepreview/{$folderPath}/{$media->file_name}");
                    $media->full_path = "{$folderPath}/{$media->file_name}";
                } else {
                    $media->path = null;
                }
                return $media;
            });
        $totalMediaCount = DB::table('media')
            ->where('model_id', $request->model_id)
            ->count();
        $totalComplete =  DB::table('completed_files')
            ->where('model_id', $request->model_id)
            ->where('user_id', $user_id)
            ->where('file_id', '<', $request->file_id) // Get previous completed files
            ->where('completed', 1) // Ensure it's completed
            ->count();
        return response()->json(['message' => 'Slide completed successfully', 'mediaHtml' => $mediaRecords, 'totalMediaCount' => $totalMediaCount, 'totalComplete' => $totalComplete], 200);
    }

    public function previousSlide(Request $request)
    {
        $user_id = Auth::id();
        $currentFileId = $request->file_id; // Get the current image ID
        $completedFiles = DB::table('completed_files')
            ->where('model_id', $request->model_id)
            ->where('user_id', $user_id)
            ->where('completed', 1)->first();
        // Find the previous media record (one ID lower than the current one)

        $previousMedia = DB::table('media')
            ->where('model_id', $completedFiles->model_id)
            ->where('id', '<', $currentFileId) // Get previous image
            ->orderBy('id', 'desc') // Get the latest previous image
            ->first();

        if (!$previousMedia) {
            return response()->json(['message' => 'No previous slides available', 'mediaHtml' => []], 200);
        }

        // Process media file path
        $folder = \LivewireFilemanager\Filemanager\Models\Folder::find($previousMedia->model_id);
        if ($folder) {
            $folderPath = buildFolderPath($folder->id);
            $previousMedia->path = url("filepreview/{$folderPath}/{$previousMedia->file_name}");
            $previousMedia->full_path = "{$folderPath}/{$previousMedia->file_name}";
        } else {
            $previousMedia->path = null;
        }
        $totalMediaCount = DB::table('media')
            ->where('model_id', $request->model_id)
            ->count();
        $previousCount = DB::table('completed_files')
            ->where('model_id', $request->model_id)
            ->where('user_id', $user_id)
            ->where('file_id', '<', $currentFileId) // Get previous completed files
            ->where('completed', 1) // Ensure it's completed
            ->count();

        return response()->json([
            'message' => 'Previous slide retrieved successfully',
            'mediaHtml' => [$previousMedia],
            'totalMediaCount' => $totalMediaCount,
            'totalComplete' => $previousCount
        ], 200);
    }
    
    // Add this method to your StudentDashboard controller
public function completeCourse($chapter_id)
{
    try {
        $user = Auth::user();
        $userId = $user->id;
        
        // Get completed files for this chapter
        $completedFiles = DB::table('completed_files')
            ->where('model_id', $chapter_id)
            ->where('user_id', $userId)
            ->where('completed', 1)
            ->get();
        
        // Get total media count
        $totalMediaCount = DB::table('files')
            ->where('folder_id', $chapter_id)
            ->count();
        
        // Get total completed count
        $totalComplete = DB::table('completed_files')
            ->where('model_id', $chapter_id)
            ->where('user_id', $userId)
            ->where('completed', 1)
            ->count();
        
        // Get completed file IDs
        $completedFileIds = $completedFiles->pluck('file_id')->toArray();
        
        // Fetch next media record (not completed yet)
        $mediaRecord = DB::table('files')
            ->where('folder_id', $chapter_id)
            ->whereNotIn('id', $completedFileIds)
            ->orderBy('id', 'asc')
            ->first();
        
        // Prepare media data
        $mediaData = null;
        if ($mediaRecord) {
            // Build folder path (using existing method)
            // $folderPath = $this->buildFolderPath($mediaRecord->folder_id);
            
            // Check if this is a Vimeo video
            $isVimeo = $mediaRecord->is_vimeo;
            $vimeoId = $mediaRecord->vimeo_id;
            
            $mediaData = [
                'id' => $mediaRecord->id,
                'model_id' => $mediaRecord->folder_id,
                'file_name' => $mediaRecord->original_name,
                'original_name' => $mediaRecord->original_name ?? $mediaRecord->name,
                'path' =>  $mediaRecord->path,
                // 'path' => url("filepreview/{$folderPath}/{$mediaRecord->file_name}"),
                // 'full_path' => $folderPath . '/' . $mediaRecord->original_name,
                'mime_type' => $mediaRecord->mime_type ?? '',
                'size' => $mediaRecord->size ?? 0,
                'is_vimeo' => $isVimeo,
                'vimeo_id' => $vimeoId,
                'vimeo_url' => $mediaRecord->vimeo_url,
                'created_at' => $mediaRecord->created_at 
            ];
        }
        // return response()->json($folderPath);
        
        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'chapter_id' => $chapter_id,
            'media' => $mediaData,
            'total_media_count' => $totalMediaCount,
            'total_complete' => $totalComplete,
            'has_next' => $mediaRecord ? true : false,
            'is_completed' => $totalComplete >= $totalMediaCount,
            'completed_files' => $completedFiles
        ]);
        
    } catch (\Exception $e) {
        Log::error('Complete course error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load course content',
            'error' => $e->getMessage()
        ], 500);
    }
}

// Helper method to build folder path (add this if not exists)
private function buildFolderPath($folderId)
{
    $path = [];
    $currentId = $folderId;
    
    while ($currentId) {
        $folder = DB::table('folders')->where('id', $currentId)->first();
        if (!$folder) break;
        
        $path[] = $folder->slug ?? Str::slug($folder->name); 
        $currentId = $folder->parent_id;
    }
    
    return implode('/',$path);
}

// Add this method for marking files as completed
// public function markFileCompleted(Request $request)
// {
//     try {
//         $request->validate([
//             'file_id' => 'required|integer',
//             'chapter_id' => 'required|integer'
//         ]);
        
//         $user = Auth::user();
        
//         // Check if already completed
//         $existing = DB::table('completed_files')
//             ->where('user_id', $user->id)
//             ->where('model_id', $request->chapter_id)
//             ->where('file_id', $request->file_id)
//             ->first();
        
//         if (!$existing) {
//             DB::table('completed_files')->insert([
//                 'user_id' => $user->id,
//                 'model_id' => $request->chapter_id,
//                 'file_id' => $request->file_id,
//                 'completed' => 1,
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);
//         }
        
//         return response()->json([
//             'success' => true,
//             'message' => 'File marked as completed'
//         ]);
        
//     } catch (\Exception $e) {
//         Log::error('Mark file completed error: ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to mark file as completed'
//         ], 500);
//     }
// }
public function markFileCompleted(Request $request)
{
    try {
        $request->validate([
            'file_id' => 'required|integer',
            'chapter_id' => 'required|integer'
        ]);
        
        $user = Auth::user();
        
        // Check if record exists
        $existing = DB::table('completed_files')
            ->where('user_id', $user->id)
            ->where('model_id', $request->chapter_id)
            ->where('file_id', $request->file_id)
            ->first();
        
        if ($existing) {
            // Update existing record - set completed to 1
            DB::table('completed_files')
                ->where('user_id', $user->id)
                ->where('model_id', $request->chapter_id)
                ->where('file_id', $request->file_id)
                ->update([
                    'completed' => 1,
                    'updated_at' => now()
                ]);
        } else {
            // Insert new record with completed = 1
            DB::table('completed_files')->insert([
                'user_id' => $user->id,
                'model_id' => $request->chapter_id,
                'file_id' => $request->file_id,
                'completed' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'File marked as completed'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Mark file completed error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark file as completed'
        ], 500);
    }
}

// Add this method for restarting chapter
public function restartChapter($chapter_id)
{
    try {
       
        $user = Auth::user();
        
        DB::table('completed_files')
            ->where('user_id', $user->id)
            ->where('model_id', $chapter_id)
            ->delete();
        return response()->json([
            'success' => true,
            'message' => 'Chapter progress reset successfully'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Restart chapter error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to reset chapter progress'
        ], 500);
    }
}

public function RESUBSCRIBE(){
        $user_id = Auth::id();
        $user = User::find($user_id);
        // Fetch the user's subscription
        $subscription = DB::table('subscriptions')
            ->where(['user_id' =>  $user->id, 'payment_status' => 'paid', 'status' => 'cancelled'])
            ->first();
        if (!$subscription) {
            // Handle case where no subscription is found
            $this->main_parent_name = [
                'errorMessage' => 'No cancelled subscription found. Please contact the admin.',
            ];
        } elseif ($subscription->user_id != $user->id) {
            // Handle invalid subscription mismatch
            $this->main_parent_name = [
                'errorMessage' => 'Invalid subscription. Please contact admin.',
            ];
        } else {
            DB::table('subscriptions')->where('id', $subscription->id)->update([
                'status' => 'active',
            ]);
              $plan = DB::table('subscription_plans')
            ->where('id',$subscription->plan_id)
            ->first();
             $token = Str::random(64);
        FacadesMail::send('emails.subscriptionRenewalReminder', ['user' => $user,'token'=>$token,'plan' =>$plan], function($message) use($user){
            $message->to($user->email);
            $message->subject('Subscription Renewal Reminder');

        });
             $this->dispatch('alertSuccess','Your subscription has been reactivated successfully!');
            return redirect()->route('student.dashboard')->with('success', 'Your subscription has been reactivated successfully!.');
        }
    }
}