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
            if (!Schema::hasColumn('users', 'type')) {
                $table->string('type')->default('user')->after('otp');
            }

            if (!Schema::hasColumn('users', 'role_id')) {
                $table->integer('role_id')->default(0)->after('type');
            }

            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image')->default(null)->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['type']);
                $table->dropColumn(['role_id']);
              

        });
    }
};
