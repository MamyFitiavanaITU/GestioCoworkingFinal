<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TruncateController extends Controller
{
    // Fonction pour afficher la vue avec le bouton de réinitialisation
    public function showResetPage()
    {
        return view('reset-database'); // Afficher la vue 'reset-database'
    }

    // Fonction pour réinitialiser la base de données
    public function truncateTables()
    {
        // Liste des tables à ne pas tronquer
        $tablesToExclude = ['admin'];

        // Obtenir la liste de toutes les tables sauf celles à exclure
        $tables = DB::select('
            SELECT tablename 
            FROM pg_tables 
            WHERE schemaname = ? 
            AND tablename NOT IN (' . implode(',', array_map(function($table) {
                return "'" . $table . "'";
            }, $tablesToExclude)) . ')', ['public']);

        DB::beginTransaction();

        try {
            // Désactiver les contraintes de clé étrangère
            DB::statement('SET CONSTRAINTS ALL DEFERRED');

            // Construire et exécuter les commandes TRUNCATE
            foreach ($tables as $table) {
                DB::statement('TRUNCATE TABLE ' . $table->tablename . ' RESTART IDENTITY CASCADE');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Les tables ont été réinitialisées avec succès (sauf la table admin).');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la réinitialisation des tables.');
        }
    }
}
