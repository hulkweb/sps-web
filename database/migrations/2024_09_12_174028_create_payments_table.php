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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Foreign key for orders
            $table->string('transaction_id')->unique(); // Unique transaction ID
            $table->decimal('amount', 10, 2); // Payment amount
            $table->string('payment_method'); // Payment method (e.g., credit card, PayPal, etc.)
            $table->string('payment_status')->default('pending'); // Payment status (e.g., pending, completed, failed)
            $table->text('response_msg')->nullable(); // Response message from payment provider
            $table->string('provider_reference_id')->nullable(); // Reference ID from the payment provider
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
