@extends('layouts')

@section('title', 'Page de Paiement')

@section('content')
<div class="container mt-5">
    <h1>Paiement de la réservation</h1>

    <!-- Affichage des messages de succès et d'erreur -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div>
        <p><strong>Espace de travail :</strong> {{ $reservation->espaceTravail->nom }}</p>
        <p><strong>Date de réservation :</strong> {{ \Carbon\Carbon::parse($reservation->dateReservation)->format('d/m/Y') }}</p>
        <p><strong>Heure de début :</strong> {{ $reservation->heureDebut }}</p>
        <p><strong>Heure de fin :</strong> 
            @php
                $heureDebut = \Carbon\Carbon::parse($reservation->heureDebut);
                $heureFin = $heureDebut->copy()->addHours($reservation->duree);
            @endphp
            {{ $heureFin->format('H:i:s') }}
        </p>
        <p><strong>Options :</strong> 
            @php
                $options = $reservation->options->pluck('nomOption')->toArray();
                $optionsList = implode(', ', $options);
            @endphp
            {{ $optionsList }}
        </p>
        <p><strong>Montant :</strong> {{ number_format($montantTotal, 2, ',', ' ') }} Ariary</p>
    </div>

    <!-- Formulaire pour saisir la référence de paiement -->
    <form action="{{ route('paiement.process', ['id' => $reservation->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="reference">Référence de Paiement :</label>
            <input type="text" id="reference" name="reference" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-valider-paiement mt-3">
    Valider le Paiement
</button>

    </form>
</div>
@endsection
