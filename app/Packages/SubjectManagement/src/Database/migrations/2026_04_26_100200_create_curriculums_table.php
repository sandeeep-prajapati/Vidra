<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculums', function (Blueprint $table) {
            $table->increments('curriculum_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('academic_year_id');
            $table->text('description')->nullable();
            $table->string('syllabus_document_path', 255)->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'academic_year_id']);
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('academic_year_id')->on('academic_years')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};
