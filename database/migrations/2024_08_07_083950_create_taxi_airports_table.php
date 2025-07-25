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
        Schema::create('taxi_airports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->unsigned();
            $table->foreignId('airport_id')->unsigned();
            $table->foreignId('driver_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_airports');
    }
};
