<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('textbooks', function (Blueprint $table) {
            $table->increments('textbook_id');
            $table->unsignedInteger('subject_id');
            $table->string('title', 150);
            $table->string('author', 100)->nullable();
            $table->string('publisher', 100)->nullable();
            $table->string('edition', 50)->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('textbook_file_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('textbooks');
    }
};
