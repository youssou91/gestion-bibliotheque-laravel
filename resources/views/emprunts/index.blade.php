@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Liste des emprunts</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Livre</th>
                <th>Utilisateur</th>
                <th>Date Emprunt</th>
                <th>Date Retour</th>
                <th>Retour Effectif</th>
                <th>Amende</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emprunts as $e)
                <tr>
                    <td>{{ $e->ouvrage->titre }}</td>
                    <td>{{ $e->utilisateur->nom ?? 'N/A' }}</td>
                    <td>{{ $e->date_emprunt }}</td>
                    <td>{{ $e->date_retour }}</td>
                    <td>{{ $e->date_effective_retour ?? '-' }}</td>
                    <td>${{ number_format($e->amende, 2) }}</td>
                    <td>{{ $e->statut }}</td>
                    <td>
                        @if($e->statut === 'en_cours')
                        <form method="POST" action="{{ url('emprunts.retour', $e->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-primary">Retourner</button>
                        </form>
                        @else
                            <span class="text-muted">Terminé</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
