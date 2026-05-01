<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('student_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('blood_group', 5)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('religion', 50)->nullable();
            $table->text('current_address');
            $table->text('permanent_address');
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('profile_photo', 255)->nullable();
            $table->date('date_of_admission');
            $table->string('admission_number', 50)->unique();
            $table->enum('status', ['Active', 'Inactive', 'Graduated', 'Transferred'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
