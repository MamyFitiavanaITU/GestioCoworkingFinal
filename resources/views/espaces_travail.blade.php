@extends('layout')

@section('title', 'Liste des Espaces de Travail')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">&larr; Retour</a>

    <h2 class="mb-4">Liste des Espaces de Travail</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix à l'heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($espaces as $espace)
                <tr>
                    <td>{{ $espace->nom }}</td>
                    <td>{{ number_format($espace->prix_heure, 2, ',', ' ') }} Ariary</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
