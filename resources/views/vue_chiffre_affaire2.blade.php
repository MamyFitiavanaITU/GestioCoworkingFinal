@extends('layout')

@section('title', 'Chiffre d\'affaire par jour')

@section('content')
    <div class="container mt-5">
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">← Retour</a>
        <h1>Chiffre d'affaire par jour</h1>

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

        <!-- Filtre par date -->
        <form action="{{ route('chiffre.affaire2') }}" method="GET">
            <div class="form-group">
                <label for="date">Sélectionnez une date :</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request()->input('date', now()->toDateString()) }}">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Voir le chiffre d'affaire</button>
        </form>

        <!-- Tableau des résultats -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Chiffre d'affaire</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($chiffreAffaire as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                        <td>{{ number_format($row->chiffre_affaire, 2, ',', ' ') }} Ariary</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Aucune donnée disponible pour cette date.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Histogramme des données -->
        <div>
            <canvas id="chiffreAffaireChart"></canvas>
        </div>

        <!-- Inclure Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('chiffreAffaireChart').getContext('2d');
                var chiffreAffaireChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dates) !!}, // Date sélectionnée
                        datasets: [{
                            label: 'Chiffre d\'affaire',
                            data: {!! json_encode($montants) !!}, // Montant du chiffre d'affaires
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) { return value + " Ariary"; }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
