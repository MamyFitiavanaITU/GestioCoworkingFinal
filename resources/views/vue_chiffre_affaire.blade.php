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
        
            <form method="GET" action="{{ route('chiffre.affaire') }}">
                <label for="date">Sélectionner une date :</label>
                <input type="date" id="date" name="date" value="{{ $date }}">
                <button type="submit">Voir</button>
            </form>
        
            <table border="1">
                <thead>
                    <tr>
                        <th>Montant Payé (€)</th>
                        <th>Montant à Payer (€)</th>
                        <th>Chiffre d'Affaires Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ number_format($chiffreAffaire->montant_paye, 2) }} €</td>
                        <td>{{ number_format($chiffreAffaire->montant_a_payer, 2) }} €</td>
                        <td>{{ number_format($chiffreAffaire->chiffre_affaire_total, 2) }} €</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endsection
        

        <!-- Histogramme des données -->
        <div>
            <canvas id="chiffreAffaireChart"></canvas>
        </div>

        <!-- Inclure Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById('chiffreAffaireChart').getContext('2d');
            var chiffreAffaireChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($dates), // Date sélectionnée
                    datasets: [{
                        label: 'Chiffre d\'affaire',
                        data: @json($montants), // Montant du chiffre d'affaires
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
        </script>
    </div>
@endsection
