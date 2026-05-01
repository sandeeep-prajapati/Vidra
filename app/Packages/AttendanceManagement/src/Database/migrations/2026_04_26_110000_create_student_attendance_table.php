<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_attendance', function (Blueprint $table) {
            $table->increments('attendance_id');
            $table->unsignedInteger('student_id');
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Leave'])->default('Present');
            $table->unsignedInteger('batch_id')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('marked_by')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendance');
    }
};
