<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Folder;
use App\Models\File;
use App\Models\SubscriptionPlan;

class PackageContentSeeder extends Seeder
{
    public function run()
    {
        // Get a subject
        $mathSubject = Subject::where('name', 'Mathematics')->first();
        
        if ($mathSubject) {
            // Create folder structure for Math
            $mathFolder = Folder::create([
                'name' => 'Mathematics',
                'subject_id' => $mathSubject->id,
                'board_id' => $mathSubject->board_id,
                'level_id' => $mathSubject->level_id,
                'path' => '/mathematics'
            ]);
            
            // Create subfolders
            $algebraFolder = Folder::create([
                'name' => 'Algebra',
                'parent_id' => $mathFolder->id,
                'subject_id' => $mathSubject->id,
                'board_id' => $mathSubject->board_id,
                'level_id' => $mathSubject->level_id,
                'path' => '/mathematics/algebra'
            ]);
            
            $geometryFolder = Folder::create([
                'name' => 'Geometry',
                'parent_id' => $mathFolder->id,
                'subject_id' => $mathSubject->id,
                'board_id' => $mathSubject->board_id,
                'level_id' => $mathSubject->level_id,
                'path' => '/mathematics/geometry'
            ]);
            
            // Create files in folders
            File::create([
                'name' => 'basic_algebra_notes.pdf',
                'original_name' => 'Basic Algebra Notes.pdf',
                'path' => '/mathematics/algebra/basic_algebra_notes.pdf',
                'mime_type' => 'application/pdf',
                'size' => 1024000,
                'folder_id' => $algebraFolder->id,
                'extension' => 'pdf',
                'subject_id' => $mathSubject->id,
                'board_id' => $mathSubject->board_id,
                'level_id' => $mathSubject->level_id
            ]);
            
            File::create([
                'name' => 'geometry_video.mp4',
                'original_name' => 'Geometry Introduction.mp4',
                'path' => '/mathematics/geometry/geometry_video.mp4',
                'mime_type' => 'video/mp4',
                'size' => 52428800,
                'folder_id' => $geometryFolder->id,
                'extension' => 'mp4',
                'is_vimeo' => false,
                'subject_id' => $mathSubject->id,
                'board_id' => $mathSubject->board_id,
                'level_id' => $mathSubject->level_id
            ]);
            
            // Assign to packages
            $basicPackage = SubscriptionPlan::where('slug', 'o-level-basic-package')->first();
            $premiumPackage = SubscriptionPlan::where('slug', 'o-level-premium-package')->first();
            
            if ($basicPackage) {
                // Basic package gets only algebra folder
                $basicPackage->accessibleFolders()->attach([$algebraFolder->id]);
                $basicPackage->accessibleFiles()->attach($algebraFolder->files->pluck('id'));
            }
            
            if ($premiumPackage) {
                // Premium package gets all math content
                $premiumPackage->accessibleFolders()->attach([$mathFolder->id, $algebraFolder->id, $geometryFolder->id]);
                $allFiles = collect([$algebraFolder->files, $geometryFolder->files])->flatten()->pluck('id');
                $premiumPackage->accessibleFiles()->attach($allFiles);
            }
        }
    }
}