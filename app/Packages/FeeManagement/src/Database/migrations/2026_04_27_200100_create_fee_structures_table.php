<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->increments('fee_structure_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('fee_category_id');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->unsignedInteger('academic_year_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
