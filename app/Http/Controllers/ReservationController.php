<?php 
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\EspaceTravail;
use App\Models\Option;
use App\Models\Client;
use App\Models\Horairecoworking;
use Illuminate\Http\Request;
use App\Models\Paiement;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class ReservationController extends Controller
{
    public function listeClient()
    {
        $clients = Client::all();
        return view('reservation', compact('clients'));
    }

    public function formulaireReservation($idEspaceTravail, $date)
    {
        $options = Option::all();
        $clients = Client::all(); 
        // dd($clients->toArray());
        return view('reservation', [
            'idEspaceTravail' => $idEspaceTravail,
            'date' => $date,
            'options' => $options,
            'clients' => $clients,
        ]);
    }
    

    public function faireReservation(Request $request)
    {
        $validated = $request->validate([
            'dateReservation' => 'required|date',
            'ref' => 'required|string|max:255',
            'heureDebut' => 'required|date_format:H:i',
            'duree' => 'required|integer|min:1|max:4',
            'idEspaceTravail' => 'required|exists:espace_travail,id',
            'idClientSelectionne' => 'nullable|exists:clients,id', 
        ]);
        $idClientSelectionne = $request->input('idClientSelectionne');
        Log::debug('validated: ' . print_r($validated, true));

        // On récupère l'heure de début et on valide qu'elle est bien dans l'intervalle 8h-18h
        $heureDebut = Carbon::createFromFormat('H:i', $validated['heureDebut']);
        $heureMin = Carbon::createFromTime(8, 0);
        $heureMax = Carbon::createFromTime(18, 0);

        if ($heureDebut->lt($heureMin) || $heureDebut->gte($heureMax)) {
            return redirect()->back()->with('error', 'L\'heure de début doit être entre 8h et 18h.');
        }

        // Vérification que le client est connecté (on suppose qu'il est stocké dans la session)
        $clientId = Session::get('client_id');
        if (!$clientId) {
            return redirect()->route('client.login')->with('error', 'Veuillez vous connecter avant de réserver.');
        }

        // Calcul de l'heure de fin en fonction de la durée de la réservation
        $dateReservation = $validated['dateReservation'];
        $duree = (int) $validated['duree'];  
        $heureFin = $heureDebut->copy()->addHours($duree);

        if ($heureFin->gte($heureMax)) {
            return redirect()->back()->with('error', 'La durée de la réservation dépasse l\'horaire disponible (jusqu\'à 18h).');
        }

        // Vérification qu'il n'y a pas de conflit avec une réservation existante pour l'espace de travail à la même date
        $reservationExistante = Reservation::where('idEspaceTravail', $validated['idEspaceTravail'])
            ->where('dateReservation', $dateReservation)
            ->where(function ($query) use ($heureDebut, $heureFin) {
                $query->where(function ($q) use ($heureDebut, $heureFin) {
                    $q->where('heureDebut', '<', $heureFin->format('H:i:s'))
                    ->whereRaw('("heureDebut" + INTERVAL \'1 hour\' * duree) > ?', [$heureDebut->format('H:i:s')]);
                });
            })
            ->exists();

        if ($reservationExistante) {
            return redirect()->back()->with('error', 'Cet horaire est déjà réservé pour cet espace de travail.');
        }

        // Création de la réservation
        $reservation = Reservation::create([
            'ref' => $validated['ref'],
            'idClient' => $clientId,  // ID du client connecté
            'idEspaceTravail' => $validated['idEspaceTravail'],
            'dateReservation' => $dateReservation,
            'heureDebut' => $validated['heureDebut'],
            'duree' => $duree,
            'statut' => 1, 
            'idClientReserve' => $validated['idClientSelectionne'],  // ID du client réservé si sélectionné, sinon null
        ]);

        // Ajout des options à la réservation, si elles sont présentes
        if ($request->has('options')) {
            $reservation->options()->attach($request->input('options'));
        }

        // Retour avec un message de succès
        return redirect()->back()->with('success', 'Réservation effectuée avec succès !');
    }

    

    public function listeReservations()
    {
        $clientId = Session::get('client_id');
        
        if (!$clientId) {
            return redirect()->route('client.login')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
        }

        $reservations = Reservation::with(['espaceTravail', 'options']) 
            ->where('idClient', $clientId)
            ->orderBy('dateReservation', 'desc')
            ->get();

        return view('listeReservations', compact('reservations'));
    }

    public function listeReservationTotal()
    {
        $reservations = Reservation::where('statut', 2)->with('espaceTravail', 'paiement', 'options')->get();
        return view('listeReservationTotal', compact('reservations'));
    }


    public function annuler($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
    
        return redirect()->back()->with('success', 'Réservation supprimée avec succès.');
    }
    

    // public function listeReservationRehetra()
    // {
    //     $reservations = Reservation::all();
    //     return view('listeReservationrehetra', compact('reservations'));
    // }
    public function listeReservationRehetra()
    {
        $reservations = Reservation::with(['espaceTravail', 'client', 'options'])->get();
        foreach ($reservations as $reservation) {
            $prixEspace = $reservation->espaceTravail->prix_heure * $reservation->duree;
            $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);
            $reservation->montant_total = $prixEspace + $prixOptions;
        }

        return view('listeReservationrehetra', compact('reservations'));
    }

    public function topCreneauxHoraires()
    {
        $topCreneaux = DB::select("SELECT * FROM top_creneaux_horaires()");
        return view('top_creneaux', compact('topCreneaux'));
    }

    public function afficherReservationsClients()
    {
        
        $reservations = DB::table('reservations_clients as rc')
            ->join('reservations as r', 'rc.idreservation', '=', 'r.id')
            ->join('clients as c1', 'rc.idclientreservant', '=', 'c1.id')
            ->join('clients as c2', 'rc.idclientreserve', '=', 'c2.id')
            ->whereColumn('rc.idclientreservant', '!=', 'rc.idclientreserve') 
            ->select(
                'r.id as reservation_id', 
                'r.ref as reservation_ref', 
                'r.dateReservation', 
                'r.heureDebut', 
                'r.duree', 
                'r.statut as reservation_statut',
                'c1.numerotelephone as client_reservant_numerotelephone',
                'c2.numerotelephone as client_reserve_numerotelephone'
            )
            ->get(); 

        return view('reservationsClients', compact('reservations')); 
    }
    public function voirReservations()
    {
        // Récupérer l'ID du client connecté depuis la session
        $clientId = Session::get('client_id');

        // Vérifier si un utilisateur est connecté
        if (!$clientId) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        // Récupérer les réservations du client connecté (soit en tant que réservant, soit en tant que réservé)
        $reservations = DB::table('vue_details_reservations')
            ->where('client_reservant_numerotelephone', function ($query) use ($clientId) {
                $query->select('numerotelephone')
                      ->from('clients')
                      ->where('id', $clientId);
            })
            ->orWhere('client_reserve_numerotelephone', function ($query) use ($clientId) {
                $query->select('numerotelephone')
                      ->from('clients')
                      ->where('id', $clientId);
            })
            ->get();

        return view('reservationsclients', compact('reservations'));
    }

    
}
