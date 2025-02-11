@extends('layout')

@section('title', 'Chiffre d\'Affaires')

@section('content')

<div class="container mt-5">
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">&larr; Retour</a>
    <h1>Chiffre d'Affaires</h1>

    <!-- Tableau des espaces de travail -->
    <h3>Chiffre d'Affaires par Espace de Travail</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nom de l'Espace de Travail</th>
                <th>Chiffre d'Affaire Total (Ariary)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chiffreAffaireEspaces as $espace)
                <tr>
                    <td>{{ $espace->espace_travail }}</td>
                    <td>{{ number_format($espace->chiffre_affaire_total, 2, ',', ' ') }} Ariary</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tableau des options -->
    <h3>Chiffre d'Affaires par Option</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nom de l'Option</th>
                <th>Chiffre d'Affaire par Option (Ariary)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chiffreAffaireOptions as $option)
                <tr>
                    <td>{{ $option->option_nom }}</td>
                    <td>{{ number_format($option->chiffre_affaire_option, 2, ',', ' ') }} Ariary</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection
