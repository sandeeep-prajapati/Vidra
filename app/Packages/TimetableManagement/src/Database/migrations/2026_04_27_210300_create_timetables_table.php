<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->increments('timetable_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('academic_year_id');
            $table->unsignedInteger('day_id');
            $table->unsignedInteger('period_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('room_id');
            $table->timestamps();

            $table->foreign('class_id')->references('class_id')->on('classes')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('cascade');
            $table->foreign('academic_year_id')->references('academic_year_id')->on('academic_years')->onDelete('cascade');
            $table->foreign('day_id')->references('day_id')->on('days')->onDelete('cascade');
            $table->foreign('period_id')->references('period_id')->on('periods')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');

            $table->unique(['class_id', 'section_id', 'academic_year_id', 'day_id', 'period_id'], 'timetable_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
