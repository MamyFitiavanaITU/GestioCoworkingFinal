@extends('layouts')

@section('content')

<div class="container">
    <h1>Réservation dEspace de Travail</h1>
    @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    
    <!-- Afficher la date sélectionnée -->
    <p><strong>Date de réservation :</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>

    <!-- Formulaire de réservation -->
    <form method="POST" action="{{ route('reservation.store') }}">

        @csrf
        <!-- Informations cachées -->
        <input type="hidden" name="idEspaceTravail" value="{{ $idEspaceTravail }}">
        <input type="hidden" name="dateReservation" value="{{ $date }}">

        <div class="form-group">
            <label for="idClientSelectionne">Réserver pour un autre client :</label>
            <select id="idClientSelectionne" name="idClientSelectionne" class="form-control">
                <option value="">-- Sélectionner un client --</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" 
                        @if($client->id == session('client_id')) selected @endif> ({{ $client->numerotelephone }})
                    </option>
                @endforeach
            </select>
        </div>
        
        
        
        <!-- Référence de la réservation -->
        <div class="form-group">
            <label for="ref">Référence :</label>
            <input type="text" id="ref" name="ref" class="form-control" value="r0030" required>
        </div>

        <!-- Sélection de lheure de début -->
        <div class="form-group">
            <label for="heureDebut">Heure de début :</label>
            <input type="time" id="heureDebut" name="heureDebut" class="form-control"  required>
        </div>

        <!-- Sélection de la durée -->
        <div class="form-group">
            <label for="duree">Durée (en heures) :</label>
            <input type="number" id="duree" name="duree" class="form-control" min="1" max="4" value="1" required>
        </div>

        <!-- Sélection des options payantes -->
        <div class="form-group">
            <label>Options payantes :</label>
            @foreach ($options as $option)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="options[]" value="{{ $option->id }}" id="option{{ $option->id }}">
                    <label class="form-check-label" for="option{{ $option->id }}">
                        {{ $option->nomOption }} 
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Confirmer la Réservation</button>
    </form>
    {{--  <a href="{{ url('/') }}" class="btn btn-primary mt-3">Retour</a>  --}}
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">← Retour</a>
</div>
@endsection
