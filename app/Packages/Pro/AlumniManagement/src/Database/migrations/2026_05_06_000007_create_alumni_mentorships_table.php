<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_mentorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_alumni_id')->constrained('alumni_profiles')->cascadeOnDelete();
            $table->unsignedBigInteger('mentee_student_id')->nullable();
            $table->string('mentee_name');
            $table->string('area_of_mentorship');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_mentorships');
    }
};
