@extends('layout')

@section('title', 'Chiffre d\'affaire Total')

@section('content')
<div class="row mb-5"> 
    <div class="col-md-6 mb-4"> 
        <button onclick="window.location.href='{{ route('chiffre.affaireParJour') }}'" class="btn btn-primary w-100">
            Voir le chiffre d’affaire payé et non payé par jour
        </button>
    </div>
    <div class="col-md-6 mb-4"> 
        <button onclick="window.location.href='{{ route('chiffre.affaireParPlageDates') }}'" class="btn btn-primary w-100">
            Voir le chiffre d’affaire payé et non payé entre 2 dates
        </button>
    </div>
</div>

<div class="container mt-5">
    <h1>Chiffre d'affaire Total</h1> 

    <!-- Tableau des chiffres -->
    <table class="table">
        <thead>
            <tr>
                <th>Chiffre d'affaire Total (Ariary)</th>
                <th>Montant Payé (Ariary)</th>
                <th>Montant à Payer (Ariary)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($chiffreAffaireTotal, 2, ',', ' ') }} Ariary</td>
                <td>{{ number_format($montantPaye, 2, ',', ' ') }} Ariary</td>
                <td>{{ number_format($montantAPayer, 2, ',', ' ') }} Ariary</td>
            </tr>
        </tbody>
    </table>

    <!-- Histogramme -->
    <canvas id="chart"></canvas>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Chiffre d\'affaire Total', 'Montant Payé', 'Montant à Payer'],
            datasets: [{
                label: 'Montant en Ariary',
                data: [
                    {{ number_format($chiffreAffaireTotal, 2, ',', '') }},
                    {{ number_format($montantPaye, 2, ',', '') }},
                    {{ number_format($montantAPayer, 2, ',', '') }}
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
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
