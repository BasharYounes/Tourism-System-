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
        Schema::create('complete__flights', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('destination');
            $table->date('travel_dates_departure');
            $table->date('travel_dates_return')->nullable();
            $table->string('reservation_type');
            $table->double('price')->unsigned();
            $table->integer('available_place')->unsigned();
            $table->foreignId('transport_id')->unsigned();
            $table->foreignId('hotel_id')->unsigned();
            $table->string('transport_company');
            $table->integer('nights')->unsigned();
            $table->json('inclusions');
            $table->json('activities');
            $table->string('famous');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complete__flights');
    }
};
