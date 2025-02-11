@extends('layouts')

@section('title', 'Valider Paiement')

@section('content')
<div class="container mt-5">
    <h1>Valider le Paiement de la Réservation</h1>

    <!-- Affichage des messages de succès et d'erreur -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>Détails de la Réservation</h3>
        </div>
        <div class="card-body">
            <p><strong>Espace de travail :</strong> {{ $reservation->espaceTravail->nom ?? 'Non spécifié' }}</p>
            <p><strong>Date de la réservation :</strong> {{ \Carbon\Carbon::parse($reservation->dateReservation)->format('d/m/Y') }}</p>
            <p><strong>Heure de début :</strong> {{ $reservation->heureDebut }}</p>
            <p><strong>Durée :</strong> {{ $reservation->duree }} heures</p>
            <p><strong>Montant total :</strong> 
                @php
                    $prixEspace = $reservation->espaceTravail->prix_heure * $reservation->duree;
                    $prixOptions = $reservation->options->sum(fn($option) => $option->prix * $reservation->duree);
                    $montantTotal = $prixEspace + $prixOptions;
                @endphp
                {{ number_format($montantTotal, 2, ',', ' ') }} Ariary
            </p>

            <!-- Informations sur le paiement -->
            @if($reservation->paiements->isEmpty())
                <p>Pas de paiement trouvé pour cette réservation.</p>
            @else
                @foreach($reservation->paiements as $paiement)
                    <p><strong>Statut du paiement :</strong>
                        @if($paiement->statutValidation === 1)
                            <span class="badge bg-warning">En attente de validation</span>
                        @elseif($paiement->statutValidation === 2)
                            <span class="badge bg-success">Payé</span>
                        @else
                            <span class="badge bg-danger">Erreur</span>
                        @endif
                    </p>
                @endforeach
            @endif

            <!-- Affichage du bouton de validation de paiement -->
            @if($reservation->statut === 2 && $reservation->paiements->first()->statutValidation === 1)
                <form action="{{ route('paiement.valider', $reservation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Valider le Paiement</button>
                </form>
            @else
                <p>Le paiement a déjà été validé ou il n'est pas en attente de validation.</p>
            @endif
        </div>
    </div>

</div>
@endsection
