<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->increments('class_subject_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('subject_id');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();

            $table->unique(['class_id', 'subject_id']);
            $table->foreign('class_id')->references('class_id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
    }
};
