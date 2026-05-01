<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_discounts', function (Blueprint $table) {
            $table->increments('student_discount_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('discount_id');
            $table->unsignedInteger('fee_structure_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_discounts');
    }
};
