<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
        ['key' => 'online_notes',     'name' => 'Online Notes',          'has_content' => true,  'icon' => 'fas fa-book',        'sort_order' => 10],
        ['key' => 'top_past_paper',   'name' => 'Top Past Papers',       'has_content' => true,  'icon' => 'fas fa-file-alt',    'sort_order' => 20],
        ['key' => 'ws_aw_bg',         'name' => 'Worksheets & Answers',  'has_content' => true,  'icon' => 'fas fa-file-excel',  'sort_order' => 30],
        ['key' => 'recorded',         'name' => 'Recorded Lectures',     'has_content' => true,  'icon' => 'fas fa-video',       'sort_order' => 40],
    ];

    foreach ($features as $f) {
        \App\Models\Feature::updateOrCreate(['key' => $f['key']], $f);
    }
    }
}
