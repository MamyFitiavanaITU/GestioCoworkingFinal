@extends('layout')

@section('title', 'Liste des Réservations')

@section('content')
<div class="container mt-5">
    <h1>Liste des Réservations a valider paiements</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <p class="text-muted">Aucune réservation a valider trouvée  .</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Espace de Travail</th>
                    <th scope="col">Date</th>
                    <th scope="col">Heure Début</th>
                    <th scope="col">Heure Fin</th>
                    <th scope="col">Durée</th>
                    <th>Options</th> 
                    <th scope="col">Montant</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Numéro Téléphone</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->espaceTravail->nom ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->dateReservation)->format('d/m/Y') }}</td>
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
                            {{ implode(', ', $reservation->options->pluck('nomOption')->toArray()) }}
                        </td>
                        <td>
                            @php
                                $prixEspace = $reservation->espaceTravail->prix_heure * $reservation->duree;
                                $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);
                                $montantTotal = $prixEspace + $prixOptions;
                            @endphp
                            {{ number_format($montantTotal, 2, ',', ' ') }} Ariary
                        </td>
                        <td>
                            <span class="badge 
                                @if($reservation->statut === 1) bg-warning
                                @elseif($reservation->statut === 2) bg-primary
                                @elseif($reservation->statut === 3) bg-success
                                @endif">
                                @if($reservation->statut === 1) À payer
                                @elseif($reservation->statut === 2) En attente
                                @elseif($reservation->statut === 3) Validée
                                @endif
                            </span>

                            @if($reservation->paiement && $reservation->paiement->statutValidation === 1)
                                <span class="badge bg-warning"> de validation</span>
                            @elseif($reservation->paiement && $reservation->paiement->statutValidation === 2)
                                <span class="badge bg-success">Payé</span>
                            @endif
                        </td>
                        
                        <td>
                            {{ $reservation->client->numerotelephone ?? 'Non attribué' }}
                        </td>

                        <td>
                            @if($reservation->statut === 2 && $reservation->paiement && $reservation->paiement->statutValidation === 1)
                                <form action="{{ route('paiement.valider', $reservation->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="background-color: #28a745; border-color: #28a745; padding: 10px 15px; border-radius: 5px;">Valider le Paiement</button>
                                </form>
                            @elseif($reservation->statut === 3)
                                <span class="badge bg-success">Réservation Validée</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
