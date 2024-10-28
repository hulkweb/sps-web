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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'popular')) {
                $table->integer('popular')->default(0)->after('stock');
            }

        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'popular')) {
                $table->integer('popular')->default(0)->after('status');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['popular']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['popular']);
        });
    }
};
