@extends('layout')

@section('content')
    <div class="container">
        <h2>Importation CSV</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
            
        <form action="{{ route('import.options') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Importer les options (CSV):</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importer Options</button>
        </form>

        <hr>

        <form action="{{ route('import.espaces') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Importer les espaces de travail (CSV):</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importer Espaces de Travail</button>
        </form>

        <hr>

        <form action="{{ route('import.reservations') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Importer les réservations (CSV):</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importer Réservations</button>
        </form>

        <hr>

        <form action="{{ route('import.paiements') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Importer les paiements (CSV):</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Importer Paiements</button>
        </form>
    </div>
@endsection
