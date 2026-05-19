<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Board;

class PackageSeeder extends Seeder
{
    public function run()
    {
        // Create a board
        $board = Board::create([
            'name' => 'Cambridge International',
            'slug' => 'cambridge-international',
            'status' => true
        ]);

        // Create levels
        $levels = [
            ['name' => 'O Level', 'slug' => 'o-level'],
            ['name' => 'AS Level', 'slug' => 'as-level'],
            ['name' => 'A2 Level', 'slug' => 'a2-level']
        ];

        foreach ($levels as $levelData) {
            $level = Level::create([
                'name' => $levelData['name'],
                'slug' => $levelData['slug'],
                'board_id' => $board->id,
                'status' => true
            ]);

            // Create subjects for each level
            $subjects = [
                ['name' => 'Mathematics', 'base_price' => 259.00],
                ['name' => 'Physics', 'base_price' => 299.00],
                ['name' => 'Chemistry', 'base_price' => 299.00],
                ['name' => 'Biology', 'base_price' => 299.00],
                ['name' => 'English', 'base_price' => 199.00]
            ];

            foreach ($subjects as $subjectData) {
                Subject::create([
                    'name' => $subjectData['name'],
                    'board_id' => $board->id,
                    'level_id' => $level->id,
                    'slug' => strtolower(str_replace(' ', '-', $subjectData['name'])),
                    'base_price' => $subjectData['base_price'],
                    'status' => true
                ]);
            }
        }

        // Create packages for O Level
        $oLevel = Level::where('slug', 'o-level')->first();
        
        // Package 1: Basic Package
        $basicPackage = SubscriptionPlan::create([
            'name' => 'O Level Basic Package',
            'slug' => 'o-level-basic-package',
            'board_id' => $board->id,
            'level_id' => $oLevel->id,
            'price' => 999.00,
            'duration' => 3,
            'duration_type' => 'months',
            'student_limit' => 1,
            'total_chapters' => 50,
            'online_notes' => true,
            'top_past_paper' => true,
            'ws_aw_bg' => false,
            'recorded' => false,
            'status' => true
        ]);

        // Add subjects to basic package with discounted prices
        $oLevelSubjects = Subject::where('level_id', $oLevel->id)->take(3)->get();
        foreach ($oLevelSubjects as $subject) {
            $basicPackage->subjects()->attach($subject->id, [
                'subject_price' => 250.00 // Discounted from base price
            ]);
        }

        // Package 2: Premium Package
        $premiumPackage = SubscriptionPlan::create([
            'name' => 'O Level Premium Package',
            'slug' => 'o-level-premium-package',
            'board_id' => $board->id,
            'level_id' => $oLevel->id,
            'price' => 2999.00,
            'duration' => 12,
            'duration_type' => 'months',
            'student_limit' => 3,
            'total_chapters' => 200,
            'online_notes' => true,
            'top_past_paper' => true,
            'ws_aw_bg' => true,
            'recorded' => true,
            'status' => true
        ]);

        // Add all subjects to premium package with higher individual prices
        $allSubjects = Subject::where('level_id', $oLevel->id)->get();
        foreach ($allSubjects as $subject) {
            $premiumPackage->subjects()->attach($subject->id, [
                'subject_price' => 399.00
            ]);
        }
    }
}