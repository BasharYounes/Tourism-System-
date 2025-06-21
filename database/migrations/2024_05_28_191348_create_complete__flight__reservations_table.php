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
        Schema::create('complete__flight__reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complete_flight_id')->unsigned();
            $table->foreignId('user_id')->unsigned();
            $table->dateTime('reservation_date');
            $table->integer('people')->unsigned();
            $table->foreignId('room_id')->unsigned();
            $table->float('reservation_cost')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complete__flight__reservations');
    }
};
