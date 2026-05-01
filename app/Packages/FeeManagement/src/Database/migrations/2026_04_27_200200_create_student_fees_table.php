<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fees', function (Blueprint $table) {
            $table->increments('student_fee_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('fee_structure_id');
            $table->decimal('amount_due', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('total_payable', 10, 2);
            $table->enum('payment_status', ['Paid', 'Pending', 'Partially Paid'])->default('Pending');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};
