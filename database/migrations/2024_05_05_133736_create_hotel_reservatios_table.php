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
        Schema::create('hotel_reservatios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->unsigned();
            $table->foreignId('user_id')->unsigned();
            $table->dateTime('reservation_date');
            $table->date('from');
            $table->date('to');
            $table->float('reservation_cost')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_reservatios');
    }
};
