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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'createBy')) {
                $table->integer('createBy')->nullable()->after('otp');
            }

        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'createBy')) {
                $table->integer('createBy')->nullable()->after('total_amount');
            }

        });

        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'createBy')) {
                $table->integer('createBy')->nullable()->after('product_id');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['createBy']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['createBy']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['createBy']);
        });
    }
};
