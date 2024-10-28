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
        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->unsignedBigInteger('country_id');
            $table->char('country_code', 2)->nullable();
            $table->string('fips_code', 200)->nullable();
            $table->string('iso2', 200)->nullable();
            $table->string('type', 200)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
            $table->char('status', 1)->default(1);
            $table->string('wikiDataId', 200)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
