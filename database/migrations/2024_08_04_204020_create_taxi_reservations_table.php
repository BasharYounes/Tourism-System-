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
        Schema::create('taxi_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->foreignId('taxi_airport_id');
            $table->foreignId('user_id');
            $table->date('date');
            $table->float('cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_reservations');
    }
};
