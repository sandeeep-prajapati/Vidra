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
        Schema::create('student_contacts', function (Blueprint $table) {
            $table->increments('contact_id');
            $table->unsignedInteger('student_id');
            $table->enum('contact_type', ['phone', 'mobile', 'emergency', 'work', 'fax', 'other'])->default('phone');
            $table->string('contact_value', 100);
            $table->boolean('is_primary')->default(false);
            $table->string('label', 50)->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->index(['student_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_contacts');
    }
};
