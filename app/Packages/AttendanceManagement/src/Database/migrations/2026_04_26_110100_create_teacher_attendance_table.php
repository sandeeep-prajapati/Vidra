<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_attendance', function (Blueprint $table) {
            $table->increments('attendance_id');
            $table->unsignedInteger('staff_id');
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Leave'])->default('Present');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['staff_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_attendance');
    }
};
