<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('previous_educations', function (Blueprint $table) {
            $table->increments('education_id');
            $table->unsignedInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->string('school_name', 150);
            $table->string('board', 100);
            $table->string('class_completed', 20);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->year('year_of_passing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_educations');
    }
};
