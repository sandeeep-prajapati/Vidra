<?php

namespace App\Packages\Pro\LibraryManagement\Database\Seeders;

use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
use Illuminate\Database\Seeder;

class LibraryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Science', 'description' => 'Science and Physics books'],
            ['name' => 'Mathematics', 'description' => 'Mathematics and Geometry books'],
            ['name' => 'Literature', 'description' => 'Literature and Languages books'],
            ['name' => 'History', 'description' => 'History and Geography books'],
            ['name' => 'Technology', 'description' => 'Computer and Technology books'],
            ['name' => 'General', 'description' => 'General knowledge books'],
        ];

        foreach ($categories as $category) {
            LibraryCategory::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
