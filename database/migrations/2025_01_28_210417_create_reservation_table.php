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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // Création de la colonne id (clé primaire)
            $table->string('ref', 255); // Référence de la réservation
            $table->unsignedBigInteger('idEspaceTravail')->nullable(); // Lien avec l'espace de travail
            $table->unsignedBigInteger('idClient')->nullable(); // Lien avec le client
            $table->date('dateReservation'); // Date de réservation
            $table->time('heureDebut'); // Heure de début de la réservation
            $table->integer('duree')->nullable(); // Durée de la réservation
            $table->integer('statut'); // Statut de la réservation
            $table->timestamps(); // Colonnes created_at et updated_at
        
            // Ajout de la colonne idClientReserve, par défaut l'idClient si non précisé
            $table->unsignedBigInteger('idClientReserve')->nullable(); // Ajout de la colonne pour réserver pour un autre client
        
            // Définition des clés étrangères
            $table->foreign('idClient')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('idEspaceTravail')->references('id')->on('espace_travail')->onDelete('cascade');
            $table->foreign('idClientReserve')->references('id')->on('clients')->onDelete('set null'); // Relation avec les clients pour la réservation
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
