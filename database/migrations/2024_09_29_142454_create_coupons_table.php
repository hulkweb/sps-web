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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Coupon code
            $table->string('title'); // Coupon title
            $table->text('description')->nullable(); // Coupon description
            $table->decimal('discount', 8, 2); // Discount value
            $table->enum('discount_type', ['percent', 'flat']); // Discount type (percentage or fixed)
            $table->date('start_date'); // Start date of the coupon
            $table->date('end_date'); // End date of the coupon
            $table->decimal('min_price', 8, 2)->default(0); // Minimum price to apply the coupon
            $table->boolean('status')->default(1); // Coupon status (active or inactive)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
