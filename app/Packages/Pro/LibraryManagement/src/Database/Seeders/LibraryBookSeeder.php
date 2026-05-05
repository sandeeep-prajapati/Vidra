<?php

namespace App\Packages\Pro\LibraryManagement\Database\Seeders;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
use Illuminate\Database\Seeder;

class LibraryBookSeeder extends Seeder
{
    public function run(): void
    {
        $science = LibraryCategory::where('name', 'Science')->first();
        $math = LibraryCategory::where('name', 'Mathematics')->first();
        $literature = LibraryCategory::where('name', 'Literature')->first();

        if ($science) {
            LibraryBook::firstOrCreate(
                ['isbn' => '978-0-13-110362-7'],
                [
                    'category_id' => $science->id,
                    'title' => 'Introduction to Physics',
                    'author' => 'Halliday & Resnick',
                    'edition' => '11th',
                    'total_copies' => 5,
                    'available_copies' => 5,
                    'rack_number' => 'A1-101',
                    'status' => 'active',
                ]
            );
        }

        if ($math) {
            LibraryBook::firstOrCreate(
                ['isbn' => '978-0-201-61622-4'],
                [
                    'category_id' => $math->id,
                    'title' => 'Calculus: Early Transcendentals',
                    'author' => 'Stewart',
                    'edition' => '9th',
                    'total_copies' => 4,
                    'available_copies' => 4,
                    'rack_number' => 'B2-205',
                    'status' => 'active',
                ]
            );
        }

        if ($literature) {
            LibraryBook::firstOrCreate(
                ['isbn' => '978-0-141-44171-6'],
                [
                    'category_id' => $literature->id,
                    'title' => 'To Kill a Mockingbird',
                    'author' => 'Harper Lee',
                    'edition' => '1st',
                    'total_copies' => 3,
                    'available_copies' => 3,
                    'rack_number' => 'C1-304',
                    'status' => 'active',
                ]
            );
        }
    }
}
