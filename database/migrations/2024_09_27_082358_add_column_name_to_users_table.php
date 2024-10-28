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
            if (!Schema::hasColumn('orders', 'payment_type')) {
                $table->string('payment_type')->default('cod')->after('status');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('status');
            }

            if (!Schema::hasColumn('orders', 'tax')) {
                $table->string('tax')->default(0)->after('status');
            }

            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_type']);
            $table->dropColumn(['payment_status']);
            $table->dropColumn(['tax']);
            $table->dropColumn(['transaction_id']);
        });
    }
};
