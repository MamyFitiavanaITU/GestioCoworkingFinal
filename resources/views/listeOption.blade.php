@extends('layout')

@section('title', 'Liste des Options')

@section('content')
<div class="container mt-5">
    <h1>Liste des Options</h1>

    @if($options->isEmpty())
        <p>Aucune option disponible.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom Option</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach($options as $option)
                    <tr>
                        <td>{{ $option->code }}</td>
                        <td>{{ $option->nomOption }}</td>
                        <td>{{ number_format($option->prix, 2, ',', ' ') }} Ariary</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<div class="col-md-6 mb-4"> 
    <button onclick="window.location.href='{{ route('chiffre.affaireEsapeOption') }}'" class="btn btn-primary w-100">
        Voir la chiffre d’affaire des espaces et options 
    </button>
</div>
<div class="col-md-6 mb-4"> 
    <button onclick="window.location.href='{{ route('espaces.travail') }}'" class="btn btn-primary w-100">
        Voir la liste des espace de travail
    </button>
</div>


@endsection
