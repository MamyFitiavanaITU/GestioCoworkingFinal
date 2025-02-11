<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option; 
use App\Models\EspaceTravail; 
use App\Models\Reservation;
use App\Models\ReservationOption;
use App\Models\Client;
use App\Models\Paiement; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class CsvImportController extends Controller
{

    public function showImportForm()
    {
        return view('import');
    }


    public function importOptions(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240', 
        ]);

        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0); 

        foreach ($csv as $row) {
            Option::create([
                'code' => $row['CODE'], 
                'nomOption' => $row['OPTION'], 
                'prix' => $row['PRIX'], 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Importation réussie pour les options!');
    }


    public function importEspaceTravail(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240', 
        ]);
        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0); 
        foreach ($csv as $row) {
            EspaceTravail::create([
                'nom' => $row['nom'], 
                'prix_heure' => $row['prix_heure'],
            ]);
        }

        return redirect()->back()->with('success', 'Importation réussie pour les espaces de travail!');
    }
    
    public function importReservations(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);
    
        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
    
        foreach ($csv as $row) {
            
            $client = Client::firstOrCreate(
                ['numerotelephone' => $row['client']],
                ['numerotelephone' => $row['client']]
            );
    
            $espace = EspaceTravail::where('nom', $row['espace'])->first();
    
            $idClientReserve = isset($row['client_reserve']) && !empty($row['client_reserve']) ? 
                Client::firstOrCreate(['numerotelephone' => $row['client_reserve']])->id : 
                $client->id;
            
            Log::debug('idClientReserve: ' . $idClientReserve);
    
            $reservation = Reservation::create([
                'ref' => $row['ref'],
                'idEspaceTravail' => $espace->id, 
                'idClient' => $client->id, 
                'idClientReserve' => $client->id,  
                'dateReservation' => Carbon::createFromFormat('d/m/Y', $row['date']), 
                'heureDebut' => $row['heure_debut'],
                'duree' => $row['duree'],
                'statut' => 1, 
            ]);

            $options = explode(',', $row['option']);
            foreach ($options as $optionCode) {
                $optionCode = strtoupper(trim($optionCode)); 
                $option = Option::where('code', $optionCode)->first(); 
    
                if ($option) {
                    $reservation->options()->attach($option->id);
                } else {
                    Log::debug('Option non trouvée pour le code : ' . $optionCode);
                }
            }
        }
    
        return redirect()->back()->with('success', 'Importation réussie pour les réservations!');
    }
    
    

    public function importPaiements(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0); 

        foreach ($csv as $row) {
            Log::info("Ligne CSV: ", $row);

            $reservation = Reservation::whereRaw('LOWER(ref) = LOWER(?)', [$row['ref']])->first();


            if ($reservation) {
                $espace = $reservation->espaceTravail;
                $prix_heure = $espace ? $espace->prix_heure : 0;

                Log::info("Prix heure espace: " . $prix_heure);
                Log::info("Durée réservation: " . $reservation->duree);

                if ($prix_heure == 0) {
                    return redirect()->back()->with('error', "Le prix_heure pour l'espace ".$reservation->idEspaceTravail." est nul ou incorrect.");
                }

                $prixEspace = $prix_heure * $reservation->duree;

                $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);

                $montantTotal = $prixEspace + $prixOptions;

                Log::info("Montant calculé (Espace + Options): " . $montantTotal);

                Paiement::create([
                    'referencesPaiements' => $row['ref_paiement'],
                    'idReservation' => $reservation->id,
                    'datePaiement' => Carbon::createFromFormat('d/m/Y', $row['date']),
                    'statutValidation' => 2,
                    'montant' => $montantTotal, 
                ]);
                $reservation->update(['statut' => 3]);
            }
        }

        return redirect()->back()->with('success', 'Importation réussie pour les paiements!');
    }


}
