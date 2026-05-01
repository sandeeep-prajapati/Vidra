<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->increments('review_id');
            $table->unsignedInteger('staff_id');
            $table->date('review_period_start');
            $table->date('review_period_end');
            $table->decimal('rating', 2, 1)->nullable();
            $table->text('comments')->nullable();
            $table->string('reviewed_by', 100)->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
