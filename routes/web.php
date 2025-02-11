<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EspaceTravailController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\TruncateController;
use App\Http\Controllers\OptionController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::get('/truncate-tables', [TruncateController::class, 'truncateTables'])->name('truncate.tables');
Route::get('/options', [OptionController::class, 'listeOption'])->name('options.index');


Route::get('/login', [ClientController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [ClientController::class, 'login']);
Route::get('/logout', [ClientController::class, 'logout'])->name('logout');


Route::get('/espaces', [EspaceTravailController::class, 'listeEspace'])->name('listeEsapce');
Route::get('/espaces-travail', [EspaceTravailController::class, 'listeEspacesTravail'])->name('espaces.travail');
// Afficher le formulaire de réservation
Route::get('/reservation/create/{idEspaceTravail}/{date}', [ReservationController::class, 'formulaireReservation'])->name('reservation.create');
//Route::post('/reserver', [ReservationController::class, 'faireReservation'])->name('reservation.store');


// Enregistrer une réservation
Route::post('/reserver', [ReservationController::class, 'faireReservation'])->name('reservation.store');
Route::get('/reservations', [ReservationController::class, 'listeReservations'])->name('reservations.liste');

Route::get('/paiement/{id}', [PaiementController::class, 'show'])->name('paiement.show');  
Route::post('/paiement/{id}/process', [PaiementController::class, 'process'])->name('paiement.process');

Route::delete('/reservation/{id}/annuler', [ReservationController::class, 'annuler'])->name('reservation.annuler');

Route::get('/reservationsliste', [ReservationController::class, 'listeReservationTotal'])->name('reservations.liste.total');
Route::get('/reservationsrehetra', [ReservationController::class, 'listeReservationRehetra'])->name('reservations.liste.rehetra');

// Route pour afficher les détails de la réservation et du paiement
Route::get('/paiement/valider/{id}', [PaiementController::class, 'showvalider'])->name('paiement.show.valider');
Route::post('/paiement/valider/{id}', [PaiementController::class, 'validerPaiement'])->name('paiement.valider');
Route::post('/paiement/{id}/refuser', [PaiementController::class, 'refuser'])->name('paiement.refuser');



//import 
Route::get('/import', [CsvImportController::class, 'showImportForm'])->name('import.form');
Route::post('/import-options', [CsvImportController::class, 'importOptions'])->name('import.options');
Route::post('/import-espaces', [CsvImportController::class, 'importEspaceTravail'])->name('import.espaces');
Route::post('/import-reservations', [CsvImportController::class, 'importReservations'])->name('import.reservations');
Route::post('/import-paiements', [CsvImportController::class, 'importPaiements'])->name('import.paiements');



// Route pour afficher la page de réinitialisation
Route::get('/reset-database', [TruncateController::class, 'showResetPage'])->name('reset.page');

// Route pour réinitialiser la base de données
Route::post('/reset-database', [TruncateController::class, 'truncateTables'])->name('reset.database');
Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('/statistiques/top-creneaux', [ReservationController::class, 'topCreneauxHoraires'])->name('statistiques.topCreneaux');
// Route::get('/chiffre-affaire', [PaiementController::class, 'getChiffreAffaire'])->name('chiffre.affaire');
Route::get('/chiffre-affaire', [PaiementController::class, 'getChiffreAffaireUnJour'])->name('chiffre.affaire');
Route::get('/chiffre-affaireParJour', [PaiementController::class, 'getChiffreAffaireUnJourUn'])->name('chiffre.affaire2');
Route::get('/chiffre-affaireDeuxDates', [PaiementController::class, 'getChiffreAffaireEntreDeuxDates'])->name('chiffre.affairedeuxdates');
Route::get('/chiffre-affaireTotalParJour', [PaiementController::class, 'chiffreAffaireTotalParJour'])->name('chiffre.affaireParJour');
Route::get('/chiffre-affaire-total', [PaiementController::class, 'chiffreAffaireTotal'])->name('chiffre.affaire.total');
Route::get('/chiffre-affaire-total-histogramme', [PaiementController::class, 'chiffreAffaireTotalHistogramme'])->name('chiffre.affaire.total.histogramme');

Route::get('/chiffre-affaire-plage-dates', [PaiementController::class, 'chiffreAffaireParPlageDates'])->name('chiffre.affaireParPlageDates');
Route::get('/chiffre-affaireEspaceOption', [PaiementController::class, 'afficherChiffreAffaire'])->name('chiffre.affaireEsapeOption');

Route::get('/chiffre-affaires', [PaiementController::class, 'chiffreAffaires'])->name('chiffre.affaireRehetra');



Route::get('/reservations/details', [ReservationController::class, 'voirReservations'])->name('reservations.details');

