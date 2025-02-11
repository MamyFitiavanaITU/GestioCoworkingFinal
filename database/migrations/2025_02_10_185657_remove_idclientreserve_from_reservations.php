<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('idClientReserve'); // Supprime la colonne
        });
    }
    
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('idClientReserve')->nullable(); // Ajoute la colonne si la migration est annulée
        });
    }
    
};
