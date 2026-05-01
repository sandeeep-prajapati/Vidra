<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_report_cards', function (Blueprint $table) {
            $table->increments('report_card_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('exam_id');
            $table->decimal('total_marks', 6, 2);
            $table->decimal('maximum_marks', 6, 2);
            $table->decimal('overall_percentage', 5, 2)->nullable();
            $table->string('overall_grade', 5)->nullable();
            $table->integer('rank_in_class')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_report_cards');
    }
};
