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
        Schema::create('reservations_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idReservation')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('idClientReservant')->constrained('clients')->onDelete('cascade');
            $table->foreignId('idClientReserve')->constrained('clients')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations_clients');
    }
};
