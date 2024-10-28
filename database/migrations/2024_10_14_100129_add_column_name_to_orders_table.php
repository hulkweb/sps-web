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
        Schema::table('assign_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('assign_orders', 'comment')) {
                $table->string('comment')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assign_orders', function (Blueprint $table) {
            $table->dropColumn(['comment']);
        });
    }
};
