<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_department_assignments', function (Blueprint $table) {
            $table->increments('assignment_id');
            $table->unsignedInteger('staff_id');
            $table->unsignedInteger('department_id');
            $table->boolean('is_primary')->default(false);
            $table->date('assigned_date')->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->unique(['staff_id', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_department_assignments');
    }
};
