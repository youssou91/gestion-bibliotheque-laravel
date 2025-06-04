@extends('frontOffice.layouts.app')
@section('content')

<body id="reportsPage">
    <div id="home">
        <div class="row">
            <div class="col-12">
                <div class="bg-white tm-block">
                    <h2 class="tm-block-title">Mes Livres Favoris</h2>
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
                        {{-- @if($favoris->isEmpty()) --}}
                            <div class="col-12">
                                <div class="alert alert-info">
                                    Vous n'avez aucun livre dans vos favoris.
                                </div>
                            </div>
                        {{-- @else --}}
                            @foreach($favoris as $favori)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0 rounded">
                                        <img src="{{ asset('assets/img/' . $favori->photo) }}" 
                                             class="card-img-top" 
                                             alt="{{ $favori->titre }}" 
                                             style="height: 350px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $favori->titre }}</h5>
                                            <p class="card-text">
                                                <small>Par {{ $favori->auteur }}</small><br>
                                                <strong>{{ $favori->stock && $favori->stock->quantite > 0 ? 'Disponible' : 'Indisponible' }}</strong>
                                            </p>
                                            
                                            <div class="d-flex align-items-center justify-content-between gap-3 mt-3 flex-wrap">
                                                <form action="{{ route('frontOffice.emprunts.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="livre_id" value="{{ $favori->id }}">
                                                    <button type="submit" class="btn btn-sm btn-success border-0 rounded"
                                                            title="Emprunter ce livre"
                                                            {{ !$favori->stock || $favori->stock->quantite <= 1 ? 'disabled' : '' }}>
                                                        <i class="fas fa-cart-plus fs-5"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('livres.favoris', $favori->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger border-0 rounded"
                                                            title="Retirer des favoris">
                                                        <i class="fas fa-heart-broken fs-5"></i>
                                                    </button>
                                                </form>
                                                
                                                <a href="{{ route('livres.show', $favori->id) }}"
                                                   class="btn btn-sm btn-outline-primary border-0 rounded"
                                                   title="Voir les détails">
                                                    <i class="fas fa-eye fs-5"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection