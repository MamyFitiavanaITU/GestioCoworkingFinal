@extends('layouts')

@section('title', 'Liste des Réservations')

@section('content')
<div class="container mt-5">
    <h1>Mes Réservations</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <p class="text-muted">Vous n'avez aucune réservation pour le moment.</p>
    @else
        <table class="simple-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Espace de Travail</th>
                    <th scope="col">Date</th>
                    <th scope="col">Heure Début</th>
                    <th scope="col">Heure Fin</th>
                    <th scope="col">Durée (h)</th>
                    <th scope="col">Montant</th>
                    <th scope="col">Options</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                    @php
                        $dateReservation = \Carbon\Carbon::parse($reservation->dateReservation);
                        $estFait = $dateReservation->isPast(); // Vérifie si la date est passée
                        $estAujourdhui = $dateReservation->isToday(); // Vérifie si la date est aujourd'hui
                        $estFuture = $dateReservation->isFuture(); // Vérifie si la date est dans le futur
                        $estPaye = $reservation->paiement && $reservation->paiement->statutValidation === 2; // Vérifie si le paiement est validé
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $reservation->espaceTravail->nom ?? 'N/A' }}</td>
                        <td>{{ $dateReservation->format('d/m/Y') }}</td>
                        <td>{{ $reservation->heureDebut }}</td>
                        <td>
                            @php
                                $heureDebut = \Carbon\Carbon::parse($reservation->heureDebut);
                                $heureFin = $heureDebut->copy()->addHours($reservation->duree);
                            @endphp
                            {{ $heureFin->format('H:i:s') }}
                        </td>
                        <td>{{ $reservation->duree }}</td>
                        <td>
                            @php
                                $prixEspace = $reservation->espaceTravail->prix_heure * $reservation->duree;
                                $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);
                                $montantTotal = $prixEspace + $prixOptions;
                            @endphp
                            {{ number_format($montantTotal, 2, ',', ' ') }} Ariary
                        </td>
                        <td>
                            @php
                                $options = $reservation->options->pluck('nomOption')->toArray();
                                $optionsList = implode(', ', $options);
                            @endphp
                            {{ $optionsList }}
                        </td>
                        <td>
                            <span class="badge 
                                @if($estFait && $estPaye) bg-secondary
                                @elseif($reservation->statut === 1) bg-warning
                                @elseif($reservation->statut === 2) bg-primary
                                @elseif($reservation->statut === 3) bg-success
                                @endif">
                                @if($estFait && $estPaye)
                                    Fait
                                @else
                                    @if($reservation->statut === 1) 
                                        À payer
                                    @elseif($reservation->statut === 2) 
                                        En attente
                                    @elseif($reservation->statut === 3) 
                                        Validé
                                    @endif
                                @endif
                            </span>
                            @if(!$estFait)
                                @if($reservation->paiement && $reservation->paiement->statutValidation === 1)
                                    <span class="badge bg-warning">En attente de validation</span>
                                @elseif($reservation->paiement && $reservation->paiement->statutValidation === 2)
                                    <span class="badge bg-success">Validé</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if(($estAujourdhui || $estFuture) && !$estPaye)
                                @if($reservation->statut === 1)
                                <a href="{{ route('paiement.show', $reservation->id) }}" class="btn btn-payer btn-sm">
                                    Payer
                                </a>                                
                                    <form action="{{ route('reservation.annuler', $reservation->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-annuler btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                            Annuler
                                        </button>                                                                              
                                    </form>
                                @elseif($reservation->statut === 2 && $reservation->paiement && $reservation->paiement->statutValidation === 1)
                                    <span class="badge bg-warning">Paiement en attente de validation</span>
                                @elseif($reservation->statut === 3)
                                    <span class="badge bg-success">Réservation Validée</span>
                                @endif
                            @else
                                <span class="text-muted">Aucune action disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
