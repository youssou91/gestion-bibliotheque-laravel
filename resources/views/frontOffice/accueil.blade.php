@extends('frontOffice.layouts.app')
@section('content')

    <body id="reportsPage">
        <div id="home">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white tm-block">
                        <form method="get" action="{{ route('ouvrages') }}"
                            class="flex flex-col md:flex-row gap-2 mb-8 justify-center">
                            <input type="text" name="search" placeholder="Rechercher un produit..."
                                value="{{ request('search') }}" class="px-4 py-2 border rounded w-full md:w-1/3">
                            <button type="submit" class="px-4 py-2 bg-blue-600  rounded">Rechercher</button>
                        </form>
                    </div>

                    <div class="container mt-5">
                        <div class="row">
                            @foreach ($ouvrages as $livre)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0 rounded">
                                        <img src="{{ asset('assets/img/' . $livre->photo) }}" class="card-img-top"
                                            alt="{{ $livre->titre }}" style="height: 350px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $livre->titre }}</h5>
                                            <p class="card-text">
                                                <small>Par {{ $livre->auteur }}</small><br>
                                                <strong>{{ $livre->quantite > 0 ? 'Disponible' : 'Indisponible' }}</strong>
                                            </p>
                                            <form action="{{ url('emprunts.store') }}" method="POST"
                                                class="d-flex align-items-center justify-content-between gap-3 mt-3 flex-wrap">
                                                @csrf
                                                <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                                                <button type="submit" class="btn btn-sm btn-success border-0 rounded"
                                                    title="Emprunter ce livre"
                                                    {{ $livre->quantite <= 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-cart-plus fs-5"></i>
                                                </button>
                                                <a href="{{ route('livres.favoris', $livre->id) }}"
                                                    class="btn btn-sm btn-outline-danger border-0 rounded"
                                                    title="Ajouter aux favoris">
                                                    <i class="fas fa-heart fs-5"></i>
                                                </a>
                                                <a href="{{ route('livres.show', $livre->id) }}"
                                                    class="btn btn-sm btn-outline-primary border-0 rounded"
                                                    title="Voir les détails">
                                                    <i class="fas fa-eye fs-5"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded"
                                                    title="Aperçu rapide" data-bs-toggle="modal"
                                                    data-bs-target="#modalLivre{{ $livre->id }}">
                                                    <i class="fas fa-info-circle fs-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL Aperçu -->
                                <div class="modal fade" id="modalLivre{{ $livre->id }}" tabindex="-1"
                                    aria-labelledby="modalLabel{{ $livre->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel{{ $livre->id }}">
                                                    {{ $livre->titre }} — Aperçu
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Fermer"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Auteur:</strong> {{ $livre->auteur }}</p>
                                                <p><strong>Description:</strong>
                                                    {{ $livre->description ?? 'Aucune description disponible.' }}
                                                </p>

                                                <!-- Formulaire de commentaire -->
                                                <form method="POST"
                                                    action="{{ route('frontOffice.ouvrages.commenter', $livre->id) }}">

                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="commentaire{{ $livre->id }}"
                                                            class="form-label">Votre commentaire :</label>
                                                        <textarea name="commentaire" id="commentaire{{ $livre->id }}" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="note{{ $livre->id }}" class="form-label">Note (1 à
                                                            5) :</label>
                                                        <select name="note" id="note{{ $livre->id }}"
                                                            class="form-select" required>
                                                            <option selected disabled value="">---Sélectionnez une note---</option>
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <option value="{{ $i }}">{{ $i }}
                                                                    étoile{{ $i > 1 ? 's' : '' }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $ouvrages->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection