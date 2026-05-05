<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_members', function (Blueprint $table) {
            $table->id();
            $table->enum('member_type', ['student', 'teacher', 'staff'])->default('student');
            $table->string('member_id')->unique();
            $table->string('membership_number')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('max_books_allowed')->default(3);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_members');
    }
};
