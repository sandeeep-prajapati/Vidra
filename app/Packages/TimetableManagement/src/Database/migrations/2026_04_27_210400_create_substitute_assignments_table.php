<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('substitute_assignments', function (Blueprint $table) {
            $table->increments('substitute_id');
            $table->unsignedInteger('timetable_id');
            $table->unsignedInteger('original_teacher_id');
            $table->unsignedInteger('substitute_teacher_id');
            $table->date('date_of_substitution');
            $table->timestamps();

            $table->foreign('timetable_id')->references('timetable_id')->on('timetables')->onDelete('cascade');
            $table->foreign('original_teacher_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('substitute_teacher_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('substitute_assignments');
    }
};
