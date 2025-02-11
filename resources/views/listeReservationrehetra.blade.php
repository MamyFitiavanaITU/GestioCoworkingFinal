@extends('layout')

@section('title', 'Liste des Réservations')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Liste des Réservations</h2>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Référence</th>
                <th>Client</th>
                <th>Espace de Travail</th>
                <th>Date</th>
                <th>Heure Début</th>
                <th>Durée</th>
                <th>Options</th> 
                <th>Montant</th> 
                <th>Statut</th>
                <th>Date de Paiement</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $reservation)
                @php
                    $dateReservation = \Carbon\Carbon::parse($reservation->dateReservation);
                    $estFait = $dateReservation->isPast() && optional($reservation->paiement)->statutValidation == 2;
                @endphp
                <tr>
                    <td>{{ $reservation->ref }}</td>
                    <td>{{ $reservation->client->numerotelephone ?? 'Non attribué' }}</td>
                    <td>{{ $reservation->espaceTravail->nom ?? 'Non défini' }}</td>
                    <td>{{ $dateReservation->format('d/m/Y') }}</td>
                    <td>{{ $reservation->heureDebut }}</td>
                    <td>{{ $reservation->duree }} heure(s)</td>
                    <td>
                        @foreach ($reservation->options->pluck('nomOption')->toArray() as $index => $option)
                            {{ $option }}@if ($index < count($reservation->options) - 1), @endif
                        @endforeach
                    </td>
                    <td>{{ number_format($reservation->montant_total, 2, ',', ' ') }} Ariary</td>
                    <td>
                        @if ($estFait)
                            <span class="badge bg-secondary">Fait</span>
                        @elseif ($reservation->statut == 1)
                            <span class="badge bg-danger">Réservé non payé</span>
                        @elseif ($reservation->statut == 2)
                            <span class="badge bg-warning">En attente</span>
                        @elseif ($reservation->statut == 3)
                            <span class="badge bg-success">Validé</span>
                        @else
                            <span class="badge bg-secondary">Inconnu</span>
                        @endif
                    </td>
                    <td>
                        {{ optional($reservation->paiement)->datePaiement 
                            ? \Carbon\Carbon::parse($reservation->paiement->datePaiement)->format('d/m/Y') 
                            : 'Non payé' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
</div>
@endsection
