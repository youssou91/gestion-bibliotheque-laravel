@extends('frontOffice.layouts.app')
@section('content')

    <body id="reportsPage">
        <div id="home">
            <div class="row">
                <div class="col-12">
                    <!-- Titre principal -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="bg-white rounded shadow-sm p-4">
                                <h2 class="mb-0">
                                    <i class="fas fa-heart ms-2 text-primary"></i>
                                    Gestion de mes Ouvrages Favoris
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-5">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row">
                            @forelse($favoris as $favori)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0 rounded">
                                        <img src="
                                            {{-- {{ asset('assets/img/' . $favori->photo) }} --}}
                                             "
                                            class="card-img-top"
                                            alt="
                                            {{-- {{ $favori->titre }} --}}
                                             "
                                            style="height: 350px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                {{-- {{ $favori->titre }} --}}
                                            </h5>
                                            <p class="card-text">
                                                <small>Par
                                                    {{-- {{ $favori->auteur }} --}}
                                                </small><br>
                                                <strong
                                                    class="
                                                    {{-- {{ $favori->stock && $favori->stock->quantite > 0 ? 'text-success' : 'text-danger' }} --}}
                                                     ">
                                                    {{-- {{ $favori->stock && $favori->stock->quantite > 0 ? 'Disponible' : 'Indisponible' }} --}}
                                                </strong>
                                            </p>

                                            <div class="d-flex justify-content-between mt-3">
                                                <form action="{{ route('frontOffice.emprunts.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="livre_id"
                                                        value="
                                                    {{-- {{ $favori->id }} --}}
                                                     ">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        {{-- {{ !$favori->stock || $favori->stock->quantite <= 0 ? 'disabled' : '' }} --}}>
                                                        <i class="fas fa-bookmark me-1"></i>
                                                    </button>
                                                </form>

                                                <form
                                                    action="
                                                {{-- {{ route('livres.favoris', $favori->id) }} --}}
                                                 "
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-heart-broken me-1"></i>
                                                    </button>
                                                </form>

                                                <a href="
                                                {{-- {{ route('livres.show', $favori->id) }} --}}
                                                 "
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-heart-broken fa-2x mb-3"></i>
                                        <h4>Vous n'avez aucun favoris pour le moment</h4>
                                        <p class="mt-3">
                                            <a href="
                                            {{ route('ouvrages') }}
                                             "
                                                class="btn btn-primary">
                                                <i class="fas fa-book me-1"></i> Parcourir les ouvrages
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
