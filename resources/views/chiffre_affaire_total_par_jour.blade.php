@extends('layout')

@section('title', 'Chiffre d\'Affaires par Jour')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">&larr; Retour</a>
    <h2 class="mb-4">Chiffre d'Affaires Payé et Non Payé par Jour</h2>

    <!-- Formulaire de sélection de la date -->
    <form method="GET" action="{{ route('chiffre.affaireParJour') }}">
        <div class="mb-3">
            <label for="date" class="form-label">Sélectionner une date :</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ $date }}">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>

    <!-- Affichage des résultats sous forme de tableau -->
    @if($chiffreAffaire)
        <div class="mt-4">
            <h4>Résultats pour le {{ $date }}</h4>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Montant Payé (MGA)</th>
                        <th>Montant à Payer (MGA)</th>
                        <th>Chiffre d'Affaires Total (MGA)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ number_format($chiffreAffaire[0]->montant_paye, 2, ',', ' ') }}</td>
                        <td>{{ number_format($chiffreAffaire[0]->montant_a_payer, 2, ',', ' ') }}</td>
                        <td>{{ number_format($chiffreAffaire[0]->chiffre_affaire_total_par_jour, 2, ',', ' ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-3 text-danger">Aucune donnée disponible pour cette date.</p>
    @endif

@endsection
