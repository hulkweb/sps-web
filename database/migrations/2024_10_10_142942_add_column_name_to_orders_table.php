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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'driver')) {
                $table->string('driver')->nullable()->after('total_amount');
            }

            if (!Schema::hasColumn('orders', 'driver_status')) {
                $table->string('driver_status')->default('pending')->nullable()->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['driver','driver_status']);
        });
    }
};
