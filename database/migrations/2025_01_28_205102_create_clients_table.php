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
       
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('numerotelephone', 15);
            $table->timestamps();
        });

        // Ajouter la contrainte CHECK sur la colonne numerotelephone
        DB::statement('ALTER TABLE clients ADD CONSTRAINT check_telephone CHECK (numerotelephone ~ \'^\+?[0-9\- ]{7,15}$\')');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Supprimer la contrainte avant de supprimer la table
        DB::statement('ALTER TABLE clients DROP CONSTRAINT check_telephone');
        Schema::dropIfExists('clients');
    }
};
