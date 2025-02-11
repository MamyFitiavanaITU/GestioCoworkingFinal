@extends('layout')

@section('title', 'Chiffre d\'affaire Total')

@section('content')
<div class="row mb-5">
    <div class="col-md-6 mb-4">
        <button onclick="window.location.href='{{ route('chiffre.affaireParJour') }}'" class="btn btn-primary w-100">
            Voir la chiffre d’affaire payé et non payé par jour
        </button>
    </div>
    <div class="col-md-6 mb-4">
        <button onclick="window.location.href='{{ route('chiffre.affaireParPlageDates') }}'" class="btn btn-primary w-100">
            Voir la chiffre d’affaire payé et non payé entre 2 dates
        </button>
    </div>
</div>

<div class="container mt-5">
    <h1>Chiffre d'affaire Total</h1>

    <!-- Affichage des valeurs -->
    <p>Montant Payé: {{ $montantPaye }}</p>
    <p>Montant à Payer: {{ $montantAPayer }}</p>
    <p>Chiffre d'Affaire Total: {{ $chiffreAffaireTotal }}</p>

    <!-- Tableau des chiffres -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Montant Payé</th>
                <th>Montant à Payer</th>
                <th>Chiffre d'Affaire Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $montantPaye }}</td>
                <td>{{ $montantAPayer }}</td>
                <td>{{ $chiffreAffaireTotal }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Histogramme -->
    <canvas id="chart" width="400" height="200"></canvas>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('chart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar', // Type d'histogramme
                data: {
                    labels: ['Chiffre d\'affaire Total', 'Montant Payé', 'Montant à Payer'],
                    datasets: [{
                        label: 'Montant en Ariary',
                        data: [
                            {{ $chiffreAffaireTotal }},
                            {{ $montantPaye }},
                            {{ $montantAPayer }}
                        ],
                        backgroundColor: ['#4e73df', '#1cc88a', '#e74a3b'],
                        borderColor: ['#4e73df', '#1cc88a', '#e74a3b'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return value.toLocaleString(); } // Format des valeurs Y
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
