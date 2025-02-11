@extends('layout')

@section('title', "Chiffre d'affaire par jour")

@section('content')
    <div class="container mt-5">
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">&larr; Retour</a>
        <h1>Chiffre d'affaire par jour - Paiements validés</h1>

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

        <form action="{{ route('chiffre.affairedeuxdates') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="date_debut">Date de début :</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control"
                        value="{{ request()->input('date_debut', $dateDebut ?? now()->startOfMonth()->toDateString()) }}">
                </div>
                <div class="col-md-4">
                    <label for="date_fin">Date de fin :</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control"
                        value="{{ request()->input('date_fin', $dateFin ?? now()->toDateString()) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Chiffre d'affaire</th>
                </tr>
            </thead>
            <tbody>
                @if ($chiffreAffaire->isNotEmpty())
                    @foreach ($chiffreAffaire as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->jour)->format('d/m/Y') }}</td>
                            <td>{{ number_format($row->chiffre_affaire_total, 2, ',', ' ') }} Ariary</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="text-center">Aucune donnée disponible.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="mt-4">
            <canvas id="chiffreAffaireChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById('chiffreAffaireChart').getContext('2d');
            var chiffreAffaireChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: "Chiffre d'affaire (Ariary)",
                        data: @json($montants),
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
                                callback: function(value) { return value.toLocaleString() + " Ariary"; }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
@endsection
