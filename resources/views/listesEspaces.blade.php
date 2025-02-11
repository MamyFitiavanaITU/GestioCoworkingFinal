@extends('layouts')

@section('content')
<div class="container">
    <h1>Liste des Espaces de Travail</h1>

    <!-- Formulaire pour choisir une date -->
    <form method="GET" action="{{ route('listeEsapce') }}">
        <div class="form-group">
            <label for="date">Choisir une date :</label>
            <input type="date" id="date" name="date" value="{{ old('date', $date) }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>

    <hr>

    <!-- Tableau des espaces de travail -->
    <table class="table-custom">
        <thead>
            <tr>
                <th>Nom de l/espace</th>
                @for ($hour = 8; $hour < 19; $hour++)
                    <th>{{ $hour }}:00</th>
                @endfor
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($espaces as $espace)
                <tr>
                    <td>{{ $espace->nom }}</td>
                    @for ($hour = 8; $hour < 19; $hour++)
                        @php
                            $statut = $horaires[$espace->id][$hour]['statut'] ?? 'Libre';
                            $estReserveParAutreClient = $horaires[$espace->id][$hour]['estReserveParAutreClient'] ?? false;
                        @endphp
                        <td class="status-cell 
                            @php
                                echo match($statut) {
                                    'Libre' => 'status-libre',
                                    'Réservé, non payé' => 'status-reserve',
                                    'Occupé' => 'status-occupe',
                                    default => ''
                                };
                            @endphp">
                            @if ($horaires[$espace->id][$hour]['afficherCroix'])
                                <span class="cross">❌</span> <!-- La croix -->
                            @endif
                        </td>

                    @endfor
                    <td class="text-center">
                        <a href="{{ route('reservation.create', ['idEspaceTravail' => $espace->id, 'date' => $date]) }}" class="btn-reserver">
                            Réserver
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Légende -->
    <div class="legend">
        <p><span> Occupé</span></p>
        <p><span> Libre</span></p>
        <p><span> Réservé, non payé</span></p>
    </div>
</div>
@endsection
