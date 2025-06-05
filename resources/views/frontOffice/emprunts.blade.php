@extends('frontOffice.layouts.app')
@section('content')

<body id="reportsPage">
    <div id="home">
        <div class="row">
            <div class="col-12">
                <div class="bg-white tm-block">
                    <h2 class="tm-block-title">Mes Emprunts en Cours 
                        <i class="fas fa-book-reader ms-2"></i>
                        <span class="badge bg-secondary">{{ $emprunts->count() }}</span>
                    </h2>
                </div>

                <div class="container mt-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif

                    <div class="row">
                        @if($emprunts->isEmpty())
                            <div class="col-12">
                                <div class="alert alert-info">
                                    Vous n'avez aucun emprunt en cours.
                                </div>
                            </div>
                        @else
                            @foreach($emprunts as $emprunt)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0 rounded">
                                        <img src="{{ asset('assets/img/' . $emprunt->ouvrage->photo) }}" 
                                             class="card-img-top" 
                                             alt="{{ $emprunt->ouvrage->titre }}" 
                                             style="height: 350px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $emprunt->ouvrage->titre }}</h5>
                                            <p class="card-text">
                                                <small>Par {{ $emprunt->ouvrage->auteur }}</small><br>
                                                <strong>Emprunté le: </strong>
                                                {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                                <br>
                                                <strong>À rendre avant: </strong>
                                                {{ $emprunt->date_retour->format('d/m/Y') }}
                                            </p>
                                            
                                            @if($emprunt->statut == 'en_cours')
                                                <form action="{{ url('frontOffice.emprunts.retour', $emprunt->id) }}" method="POST" class="mt-3">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary border-0 rounded">
                                                        <i class="fas fa-undo-alt me-1"></i> Retourner ce livre
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success">Retourné</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection