@extends('layout')

@section('title', 'Chiffre d\'Affaires')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Chiffre d'Affaires Total</h2>
    
    <!-- Affichage du chiffre d'affaires total -->
    <p><strong>Chiffre d'affaires total : </strong>{{ number_format($chiffreAffaires, 2, ',', ' ') }} Ariary</p>

    <!-- Canvas pour l'histogramme -->
    <canvas id="histogramme" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('histogramme').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar', // Type de graphique (histogramme)
        data: {
            labels: @json($paiements->pluck('referencesPaiements')), // Utilisation des références de paiements comme labels
            datasets: [{
                label: 'Montant des paiements',
                data: @json($paiements->pluck('montant')), // Montants des paiements
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Couleur des barres
                borderColor: 'rgba(54, 162, 235, 1)', // Couleur du contour des barres
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString(); // Format des montants avec séparateurs de milliers
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
