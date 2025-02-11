@extends('layout')

@section('title', 'Chiffre d\'Affaires par Plage de Dates')

@section('content')

    <h2 class="mb-4">Chiffre d'Affaires Payé et Non Payé entre deux Dates</h2>

    <!-- Formulaire de sélection des dates -->
    <form method="GET" action="{{ route('chiffre.affaireParPlageDates') }}">
        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" class="form-control" value="{{ $dateDebut }}">
        </div>
        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" class="form-control" value="{{ $dateFin }}">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>

    <!-- Affichage des résultats sous forme de tableau -->
    @if($chiffreAffaire)
        <div class="mt-4">
            <h4>Résultats entre le {{ $dateDebut }} et le {{ $dateFin }}</h4>
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
                        <td>{{ number_format($chiffreAffaire[0]->chiffre_affaire_total, 2, ',', ' ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-3 text-danger">Aucune donnée disponible pour cette plage de dates.</p>
    @endif

@endsection
