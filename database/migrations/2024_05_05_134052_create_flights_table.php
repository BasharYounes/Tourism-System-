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
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('flight_number')->nullable();
            $table->string('airline');
            $table->string('website');
            $table->string('departure_airport');
            $table->dateTime('departure_time');
            $table->date('departure_date');
            $table->string('arrival_airport');
            $table->dateTime('arrival_time')->nullable();
            $table->string('duration');
            $table->string('reservation_type');
            $table->float('price')->unsigned();
            $table->integer('available_place')->unsigned();
            $table->foreignId('transport_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
