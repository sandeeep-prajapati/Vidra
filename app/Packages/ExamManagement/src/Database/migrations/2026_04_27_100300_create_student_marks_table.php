<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_marks', function (Blueprint $table) {
            $table->increments('mark_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('schedule_id');
            $table->decimal('marks_obtained', 5, 2);
            $table->string('grade', 5)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_marks');
    }
};
