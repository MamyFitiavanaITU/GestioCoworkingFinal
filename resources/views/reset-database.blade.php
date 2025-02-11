@extends('layout') <!-- Ou une autre vue parent -->

@section('title', 'Réinitialiser la base de données')

@section('content')
    <div class="container">
        <h2>Réinitialiser la base de données</h2>

        <!-- Affichage des messages de succès ou d'erreur -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Formulaire pour réinitialiser la base -->
        <form action="{{ route('reset.database') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser la base de données ? Cette action est irréversible.')">
            @csrf
            <button type="submit" class="btn btn-danger">Réinitialiser la base de données</button>
        </form>
    </div>
@endsection
