@extends('layouts')

@section('content')
<div class="container">
    <h2>Mes Réservations</h2>

    <table class="simple-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Date</th>
                <th>Heure Début</th>
                <th>Durée</th>
                <th>Statut</th>
                <th>Espace</th>
                <th>Réservé Pour</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->reservation_ref }}</td>
                    <td>{{ $reservation->dateReservation }}</td>
                    <td>{{ $reservation->heureDebut }}</td>
                    <td>{{ $reservation->duree }} h</td>
                    <td>{{ $reservation->reservation_statut == 1 ? 'Non payé' : 'Payé' }}</td>
                    <td>{{ $reservation->espace_nom }}</td>
                    <td>{{ $reservation->client_reserve_numerotelephone ?? 'Moi-même' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
