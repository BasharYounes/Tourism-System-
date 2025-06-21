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
        Schema::create('room__hotel_complete_flights', function (Blueprint $table) {
            $table->id();
            $table->integer('capacety')->unsigned();
            $table->boolean('active')->default(false);
            $table->foreignId('hotel_id')->unsigned();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room__hotel_complete_flights');
    }
};
