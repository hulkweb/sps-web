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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key for users
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2); // Total order amount
            $table->string('status')->default('pending'); // Order status (e.g., pending, completed, canceled)
            $table->text('shipping_address')->nullable(); // Shipping address for the order
            $table->timestamp('ordered_at')->nullable(); // Date and time when the order was placed
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
