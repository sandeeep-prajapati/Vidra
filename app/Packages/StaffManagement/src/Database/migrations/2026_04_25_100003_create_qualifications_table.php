<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualifications', function (Blueprint $table) {
            $table->increments('qualification_id');
            $table->unsignedInteger('staff_id');
            $table->string('degree', 100);
            $table->string('specialization', 100)->nullable();
            $table->string('university_name', 150);
            $table->year('year_of_completion');
            $table->timestamps();

            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualifications');
    }
};
