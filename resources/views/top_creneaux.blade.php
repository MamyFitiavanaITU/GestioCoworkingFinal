@extends('layout')

@section('content')
    <div class="container">
        <!-- Premier groupe de boutons en haut -->
        {{--  <div class="row mb-5"> <!-- Augmentation de l'espacement en bas avec mb-5 -->
            <div class="col-md-6 mb-4"> <!-- Augmentation de l'espacement en bas avec mb-4 -->
                <button onclick="window.location.href='{{ route('chiffre.affaire') }}'" class="btn btn-primary w-100">
                    Voir la chiffre d’affaire par jour valide
                </button>
            </div>  --}}
            <div class="col-md-6 mb-4"> <!-- Augmentation de l'espacement en bas avec mb-4 -->
                <button onclick="window.location.href='{{ route('chiffre.affaire2') }}'" class="btn btn-primary w-100">
                    Voir la chiffre d’affaire par jour
                </button>
            </div>
        </div>

        <!-- Deuxième groupe de boutons en bas avec espacement -->
        <div class="row mb-5"> <!-- Augmentation de l'espacement en bas avec mb-5 -->
            <div class="col-md-6 mb-4"> <!-- Augmentation de l'espacement en bas avec mb-4 -->
                <button onclick="window.location.href='{{ route('chiffre.affaire.total') }}'" class="btn btn-primary w-100">
                    Voir la chiffre d’affaire payé et non payé
                </button>
            </div>
            <div class="col-md-6 mb-4"> <!-- Augmentation de l'espacement en bas avec mb-4 -->
                <button onclick="window.location.href='{{ route('chiffre.affairedeuxdates') }}'" class="btn btn-primary w-100">
                    Voir la chiffre d’affaire entre deux dates
                </button>
            </div>
        </div>

        <!-- Tableau avec les créneaux horaires -->
        <h2 class="mb-4">Top 3 des créneaux horaires les plus réservés</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Créneau Horaire</th>
                    <th>Nombre de Réservations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCreneaux as $creneau)
                    <tr>
                        <td>{{ $creneau->creneau }}</td>
                        <td>{{ $creneau->nombre_reservations }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection