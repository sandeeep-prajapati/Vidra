<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('staff_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->date('joining_date')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('designation', 100)->nullable();
            $table->enum('employment_type', ['Permanent', 'Temporary', 'Contract'])->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Resigned'])->default('Active');
            $table->string('photo', 255)->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
