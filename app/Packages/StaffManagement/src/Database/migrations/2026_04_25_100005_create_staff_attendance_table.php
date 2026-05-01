<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->increments('attendance_id');
            $table->unsignedInteger('staff_id');
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'On Leave', 'Late']);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
    }
};
