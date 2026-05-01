<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->unsignedInteger('student_fee_id');
            $table->date('payment_date');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_mode', ['Cash', 'Card', 'UPI', 'Bank Transfer']);
            $table->string('transaction_reference', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
