<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circulars', function (Blueprint $table) {
            $table->increments('circular_id');
            $table->string('title', 150);
            $table->text('content');
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->date('issued_date');
            $table->enum('target_audience', ['All', 'Students', 'Parents', 'Teachers', 'Staff'])->default('All');
            $table->string('attachment_url', 255)->nullable();
            $table->timestamps();

            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circulars');
    }
};
