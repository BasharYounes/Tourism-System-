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
        Schema::create('flight_reservatios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->unsigned();
            $table->foreignId('user_id')->unsigned();
            $table->dateTime('reservation_date');
            $table->integer('people')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_reservatios');
    }
};
