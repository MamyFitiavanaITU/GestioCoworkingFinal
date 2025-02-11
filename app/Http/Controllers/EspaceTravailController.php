<?php
namespace App\Http\Controllers;

use App\Models\EspaceTravail;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class EspaceTravailController extends Controller
{
    public function listeEspace(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $espaces = EspaceTravail::with('reservations')->get();
        $reservations = Reservation::getReservationsByDate($date);
        $clientId = Session::get('client_id');

        $horaires = $this->genererHoraires($espaces, $reservations, $clientId);

        return view('listesEspaces', compact('espaces', 'horaires', 'date'));
    }

    private function genererHoraires($espaces, $reservations, $clientId)
    {
        $horaires = [];

        foreach ($espaces as $espace) {
            $horaires[$espace->id] = [];

            for ($hour = 8; $hour < 19; $hour++) {
                $statut = 'Libre'; 
                $estReserveParAutreClient = false;  
                $afficherCroix = false;

                foreach ($reservations as $reservation) {
                    $heureDebut = Carbon::parse($reservation->heureDebut);
                    $heureFin = $heureDebut->copy()->addHours($reservation->duree);

                    if ($reservation->idEspaceTravail == $espace->id && $heureDebut->hour <= $hour && $heureFin->hour > $hour) {
                        Log::debug('Vérification de la réservation', [
                            'idClientReserve' => $reservation->idClientReserve,
                            'clientId' => $clientId,
                            'idClient' => $reservation->idClient,
                        ]);

                        if (($reservation->idClient == $clientId && $reservation->idClientReserve != $clientId) || 
                            ($reservation->idClientReserve == $clientId && $reservation->idClient != $clientId)) {
                            $estReserveParAutreClient = true;
                            $afficherCroix = true;
                        } else {
                            $estReserveParAutreClient = $reservation->idClient != $clientId;
                            $afficherCroix = false;
                        }

                        $statut = match($reservation->statut) {
                            1, 2 => 'Réservé, non payé',
                            3 => 'Occupé',
                            default => 'Libre'
                        };

                        break;
                    }
                }

                $horaires[$espace->id][$hour] = [
                    'statut' => $statut,
                    'estReserveParAutreClient' => $estReserveParAutreClient,  
                    'afficherCroix' => $afficherCroix,  
                ];
            }
        }

        return $horaires;
    }
    
    
    public function listeEspacesTravail()
    {
        $espaces = EspaceTravail::all();
        return view('espaces_travail', compact('espaces'));
    }
}
