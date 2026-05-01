<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subject_mappings', function (Blueprint $table) {
            $table->increments('mapping_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'class_id', 'subject_id', 'section_id'], 'unique_teacher_class_subject_section');
            $table->foreign('teacher_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('class_id')->references('class_id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_mappings');
    }
};
