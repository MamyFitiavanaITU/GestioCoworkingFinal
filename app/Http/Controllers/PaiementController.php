<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    // Afficher le paiement et la réservation
    public function show($id)
    {
        $reservation = Reservation::with('espaceTravail', 'options')->findOrFail($id);
        
        // Vérifier si un paiement existe
        $paiement = Paiement::where('idReservation', $reservation->id)->first();

        // Si pas de paiement, on le considère comme "en attente" (statutValidation = 1)
        $paiementStatut = $paiement ? $paiement->statutValidation : 1; 

        // Calcul du montant total de la réservation
        $prixEspace = $reservation->espaceTravail->prix_heure * $reservation->duree;
        $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);
        $montantTotal = $prixEspace + $prixOptions;

        // Retourner la vue avec les informations nécessaires
        return view('paiement', compact('reservation', 'montantTotal', 'paiementStatut'));
    }

    // Traitement du paiement
    public function process(Request $request, $id)
{
    $request->validate([
        'reference' => 'required|string|max:255',
    ]);

    $reservation = Reservation::findOrFail($id);

    if ($reservation->statut == 2) {
        return redirect()->route('paiement.show', $reservation->id)->with('error', 'Cette réservation est déjà en attente de validation.');
    }

    // Calcul du montant
    $espace = $reservation->espaceTravail;
    $prix_heure = $espace ? $espace->prix_heure : 0;
    $prixEspace = $prix_heure * $reservation->duree;

    // Calcul du prix des options
    $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);

    // Montant total
    $montantTotal = $prixEspace + $prixOptions;

    // Création du paiement
    Paiement::create([
        'idReservation' => $reservation->id,
        'referencesPaiements' => $request->reference,
        'datePaiement' => Carbon::now(),
        'statutValidation' => 1,
        'montant' => $montantTotal,  // Ajout du montant calculé
    ]);

    // Mettre à jour le statut de la réservation
    $reservation->update(['statut' => 2]);

    return redirect()->route('paiement.show', $reservation->id)->with('success', 'Paiement effectué et en attente de validation !');
}

    public function showvalider($id)
    {
        $reservation = Reservation::with('espaceTravail', 'paiements', 'options')->findOrFail($id);
        return view('valider', compact('reservation'));
    }
    
    public function validerPaiement($id)
    {
        $reservation = Reservation::findOrFail($id);
        $paiement = Paiement::where('idReservation', $reservation->id)->first();

        if ($paiement) {
            $paiement->statutValidation = 2;
            $paiement->save();
            $reservation->statut = 3;  
            $reservation->save();

            return redirect()->route('reservations.liste.total')
                            ->with('success', 'Paiement validé et réservation confirmée.');
        }
        return redirect()->route('reservations.liste.total')
                        ->with('error', 'Paiement non trouvé.');
    }

    

    // public function getChiffreAffaire(Request $request)
    // { 
    //     // Récupérer la date spécifique, avec une valeur par défaut
    //     $date = $request->input('date', '2025-01-01');
        
    //     // Exécuter la requête SQL pour récupérer les chiffres d'affaire pour cette date
    //     $chiffreAffaire = DB::table('paiements')
    //                         ->select(DB::raw('DATE("datePaiement") AS date'), DB::raw('SUM(montant) AS chiffre_affaire'))
    //                         ->where('statutValidation', 2)
    //                         ->whereDate('datePaiement', '=', $date) // Vérifier si la date est égale à la date spécifiée
    //                         ->groupBy(DB::raw('DATE("datePaiement")'))
    //                         ->orderBy(DB::raw('DATE("datePaiement")'))
    //                         ->get();

    //     // Retourner la vue avec les données récupérées
    //     return view('vue_chiffre_affaire', compact('chiffreAffaire'));
    // }


    public function getChiffreAffaireUnJourUn(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        // Exécuter la requête SQL pour récupérer le chiffre d'affaire d'un seul jour avec statutValidation = 2
        $chiffreAffaire = DB::table('paiements')
            ->selectRaw('DATE("datePaiement") AS date, COALESCE(SUM(montant), 0) AS chiffre_affaire')
            ->where('statutValidation', 2)  // Filtrage sur le statutValidation = 2
            ->whereRaw('"datePaiement"::date = ?', [$date])
            ->groupByRaw('DATE("datePaiement")')
            ->orderByRaw('DATE("datePaiement")')
            ->get();

        $dates = $chiffreAffaire->pluck('date')->toArray();
        $montants = $chiffreAffaire->pluck('chiffre_affaire')->toArray();

        return view('vue_chiffre_affaire2', compact('chiffreAffaire', 'dates', 'montants'));
    }


  
    public function getChiffreAffaireUnJour(Request $request)
    {
        // Récupération de la date fournie ou par défaut aujourd'hui
        $date = $request->input('date', now()->toDateString());

        // Exécuter la fonction PostgreSQL pour récupérer le chiffre d'affaires du jour
        $chiffreAffaire = DB::select("SELECT * FROM calcul_chiffre_affaire_totalunjour(?)", [$date]);

        // Vérifier si la fonction retourne des données
        if (!empty($chiffreAffaire)) {
            $chiffreAffaire = $chiffreAffaire[0]; // Extraire l'unique ligne de résultat
        } else {
            // Valeurs par défaut si aucune donnée trouvée
            $chiffreAffaire = (object) [
                'montant_paye' => 0,
                'montant_a_payer' => 0,
                'chiffre_affaire_total' => 0
            ];
        }
        return view('vue_chiffre_affaire', compact('chiffreAffaire', 'date'));
    }

    public function chiffreAffaireTotal()
    {
        // Appel de la fonction SQL 'calcul_chiffre_affaire_total'
        $result = DB::select('SELECT * FROM calcul_chiffre_affaire_total()');

        // Extraction des résultats
        $montantPaye = $result[0]->montant_paye;
        $montantAPayer = $result[0]->montant_a_payer;
        $chiffreAffaireTotal = $result[0]->chiffre_affaire_total;

        // Retourner la vue avec les données calculées
        return view('chiffre_affaire_total', compact('montantPaye', 'montantAPayer', 'chiffreAffaireTotal'));
    }
        

    public function chiffreAffaireTotalHistogramme()
    {
        // Récupérer les données du chiffre d'affaire
        $chiffreAffaire = DB::select("
            SELECT 
                SUM(CASE WHEN \"statutValidation\" = 2 THEN montant ELSE 0 END) AS montant_paye,
                SUM(CASE WHEN \"statutValidation\" = 1 THEN montant ELSE 0 END) AS montant_a_payer,
                SUM(montant) AS chiffre_affaire_total
            FROM paiements
        ");

        // Assurez-vous que nous avons des données
        if (count($chiffreAffaire) > 0) {
            $montantPaye = $chiffreAffaire[0]->montant_paye ?? 0;
            $montantAPayer = $chiffreAffaire[0]->montant_a_payer ?? 0;
            $chiffreAffaireTotal = $chiffreAffaire[0]->chiffre_affaire_total ?? 0;
        } else {
            $montantPaye = 0;
            $montantAPayer = 0;
            $chiffreAffaireTotal = 0;
        }

        // Passer les données à la vue
        return view('chiffre_affaire_total_histogramme', compact('montantPaye', 'montantAPayer', 'chiffreAffaireTotal'));
    }


    public function chiffreAffaireTotalParJour(Request $request)
    {
        // Récupère la date envoyée par l'input, sinon utilise la date du jour
        $date = $request->input('date', now()->toDateString());

        // Exécute la requête SQL avec la date sélectionnée
        $chiffreAffaire = DB::select("
            SELECT 
                SUM(CASE WHEN \"statutValidation\" = 2 THEN montant ELSE 0 END) AS montant_paye,
                SUM(CASE WHEN \"statutValidation\" = 1 THEN montant ELSE 0 END) AS montant_a_payer,
                SUM(montant) AS chiffre_affaire_total_par_jour
            FROM paiements
            WHERE \"datePaiement\" = ?
        ", [$date]);

        return view('chiffre_affaire_total_par_jour', compact('chiffreAffaire', 'date'));
    }


    public function chiffreAffaireParPlageDates(Request $request)
    {
        // Récupère les dates de début et de fin de la plage, avec des valeurs par défaut
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->toDateString());
        $dateFin = $request->input('date_fin', now()->toDateString());

        // Appel de la fonction PostgreSQL
        $chiffreAffaire = DB::select("
            SELECT * FROM calcul_chiffre_affaire_totalparjour(?, ?)
        ", [$dateDebut, $dateFin]);

        // Retourne la vue avec les résultats
        return view('chiffre_affaire_par_plage_dates', compact('chiffreAffaire', 'dateDebut', 'dateFin'));
    }


    public function getChiffreAffaireEntreDeuxDates(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->toDateString());
        $dateFin = $request->input('date_fin', now()->endOfMonth()->toDateString());

        $chiffreAffaire = DB::table('paiements')
            ->selectRaw('DATE("datePaiement") AS jour, COALESCE(SUM(montant), 0) AS chiffre_affaire_total')
            ->where('statutValidation', 2)
            ->whereBetween(DB::raw('DATE("datePaiement")'), [$dateDebut, $dateFin])
            ->groupByRaw('DATE("datePaiement")')
            ->orderByRaw('DATE("datePaiement")')
            ->get();

        // Extraction des données pour Chart.js
        $dates = $chiffreAffaire->pluck('jour')->toArray();
        $montants = $chiffreAffaire->pluck('chiffre_affaire_total')->toArray();

        return view('vue_chiffre_affaire_deuxdate', compact('chiffreAffaire', 'dateDebut', 'dateFin', 'dates', 'montants'));
    }

    public function refuser($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->paiement) {
            // Mettre à jour le statut du paiement
            $reservation->paiement->statutValidation = 0; // 0 = Annulé
            $reservation->paiement->save();

            // Mettre à jour le statut de la réservation si nécessaire
            $reservation->statut = 1; // Remettre la réservation à "À payer"
            $reservation->save();

            return redirect()->back()->with('success', 'Le paiement a été annulé avec succès.');
        }

        return redirect()->back()->with('error', 'Aucun paiement trouvé pour cette réservation.');
    }

    public function afficherChiffreAffaire()
    {
        // Requête pour le chiffre d'affaire par espace de travail
        $chiffreAffaireEspaces = DB::select("
            SELECT 
                e.\"nom\" AS espace_travail,
                SUM(p.\"montant\") AS chiffre_affaire_total
            FROM 
                espace_travail e
            LEFT JOIN 
                reservations r ON e.id = r.\"idEspaceTravail\"
            LEFT JOIN 
                paiements p ON r.id = p.\"idReservation\"
            GROUP BY 
                e.\"nom\"
            ORDER BY 
                e.\"nom\" ASC
        ");

        $chiffreAffaireOptions = DB::select("
            SELECT 
                o.\"nomOption\" AS option_nom,
                SUM(o.\"prix\" * r.\"duree\") AS chiffre_affaire_option
            FROM 
                options o
            JOIN 
                reservation_option ro ON o.id = ro.\"option_id\"
            JOIN 
                reservations r ON ro.\"reservation_id\" = r.id
            GROUP BY 
                o.\"nomOption\"
            ORDER BY 
                o.\"nomOption\" ASC
        ");

        // Retourner la vue avec les données
        return view('chiffre_affaire_Espace_Option', compact('chiffreAffaireEspaces', 'chiffreAffaireOptions'));
    }
    public function chiffreAffaires()
    {
        $paiements = Paiement::where('statutValidation', 2)->get();
        $chiffreAffaires = $paiements->sum('montant');
        return view('chiffre-affaires_Total', compact('paiements', 'chiffreAffaires'));
    }

    
}
