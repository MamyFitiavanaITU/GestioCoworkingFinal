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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('idReservation'); 
            $table->string('referencesPaiements', 255); 
            $table->date('datePaiement'); 
            $table->integer('statutValidation'); 
            $table->decimal('montant', 10, 2);
            $table->timestamps(); 

            $table->foreign('idReservation')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
