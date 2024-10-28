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
        Schema::create('delivery_images', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('driver_id');
            $table->string('image');
            $table->text('address')->nullable();
            $table->text('address2')->nullable();
            $table->char('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_images');
    }
};
