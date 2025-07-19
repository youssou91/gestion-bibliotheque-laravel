@extends('frontOffice.layouts.app')
@section('content')

    <div id="home" class="container py-4">
        <!-- Titre principal -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white rounded shadow-sm p-4">
                    <h2 class="mb-0">
                        <i class="fas fa-book-reader me-2 text-primary"></i>
                        Gestion de mes emprunts
                    </h2>
                </div>
            </div>
        </div>

        <!-- Messages d'alerte -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Navigation par onglets -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="empruntsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current"
                            type="button" role="tab">
                            <i class="fas fa-clock me-2"></i>Emprunts en cours
                            <span class="badge bg-primary ms-2">
                                {{ $empruntsEnCours->count() }}
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                            type="button" role="tab">
                            <i class="fas fa-history me-2"></i>Emprunts retournés
                            <span class="badge bg-secondary ms-2">{{ $empruntsHistorique->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content bg-white p-4 rounded-bottom shadow-sm">
                    <!-- Onglet Emprunts en cours -->
                    {{-- <div class="tab-pane fade show active" id="current" role="tabpanel">
                        @if ($empruntsEnCours->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez aucun emprunt en cours.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($empruntsEnCours as $emprunt)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/img/' . $emprunt->ouvrage->photo) }}"
                                                    class="card-img-top" alt="{{ $emprunt->ouvrage->titre }}"
                                                    style="height: 250px; object-fit: cover;"> <span
                                                    class="position-absolute top-0 start-0 bg-warning text-dark p-2 small">
                                                    À rendre avant: {{ $emprunt->date_retour->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $emprunt->ouvrage->titre }}</h5>
                                                <p class="card-text text-muted">
                                                    <small>Auteur: {{ $emprunt->ouvrage->auteur }}</small>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        Emprunté le: {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                                    </small>
                                                    <form action="{{ route('frontOffice.emprunts.retour', $emprunt->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-undo-alt me-1"></i> Retourner
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}
                    {{-- <div class="tab-pane fade show active" id="current" role="tabpanel">
                        @if ($empruntsEnCours->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez aucun emprunt en cours.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($empruntsEnCours as $emprunt)
                                    @php
                                        $enRetard = $emprunt->statut === 'en_retard';
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div
                                            class="card h-100 shadow-sm border-0 {{ $enRetard ? 'border border-danger' : '' }}">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/img/' . $emprunt->ouvrage->photo) }}"
                                                    class="card-img-top" alt="{{ $emprunt->ouvrage->titre }}"
                                                    style="height: 250px; object-fit: cover;">
                                                <span
                                                    class="position-absolute top-0 start-0 
                                {{ $enRetard ? 'bg-danger' : 'bg-warning' }} 
                                text-white p-2 small">
                                                    {{ $enRetard ? 'En retard depuis : ' : 'À rendre avant : ' }}
                                                    {{ $emprunt->date_retour->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    {{ $emprunt->ouvrage->titre }}
                                                </h5>
                                                <p class="card-text text-muted">
                                                    <small>Auteur : {{ $emprunt->ouvrage->auteur }}</small>
                                                </p>
                                                @if ($enRetard)
                                                    <div class="alert alert-danger py-1 px-2 mb-2 small">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Cet emprunt est en retard !
                                                    </div>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        Emprunté le : {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                                    </small>
                                                    <form action="{{ route('frontOffice.emprunts.retour', $emprunt->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $enRetard ? 'btn-danger' : 'btn-outline-primary' }}">
                                                            <i class="fas fa-undo-alt me-1"></i> Retourner
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}
                    <div class="tab-pane fade show active" id="current" role="tabpanel">
                        @if ($empruntsEnCours->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez aucun emprunt en cours.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($empruntsEnCours as $emprunt)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div
                                            class="card h-100 border-0 shadow-sm {{ $emprunt->statut === 'en_retard' ? 'border-danger' : '' }}">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/img/' . $emprunt->ouvrage->photo) }}"
                                                    class="card-img-top" alt="{{ $emprunt->ouvrage->titre }}"
                                                    style="height: 250px; object-fit: cover;">
                                                <span
                                                    class="position-absolute top-0 start-0 {{ $emprunt->statut === 'en_retard' ? 'bg-danger text-white' : 'bg-warning text-dark' }} p-2 small">
                                                    À rendre avant : {{ $emprunt->date_retour->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $emprunt->ouvrage->titre }}</h5>
                                                <p class="card-text text-muted mb-2">
                                                    <small>Auteur : {{ $emprunt->ouvrage->auteur }}</small>
                                                </p>

                                                @if ($emprunt->statut === 'en_retard')
                                                    <div class="alert alert-danger py-1 px-2 mb-2 small">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        En retard — Amende :
                                                        <strong>{{ number_format($emprunt->amende_courante, 2) }}
                                                            $</strong>
                                                    </div>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        Emprunté le : {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                                    </small>
                                                    <form action="{{ route('frontOffice.emprunts.retour', $emprunt->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $emprunt->statut === 'en_retard' ? 'btn-outline-danger' : 'btn-outline-primary' }}">
                                                            <i class="fas fa-undo-alt me-1"></i> Retourner
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Onglet Historique -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        @if ($empruntsHistorique->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez aucun emprunt dans votre historique.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Livre</th>
                                            <th>Date emprunt</th>
                                            <th>Date retour</th>
                                            <th>Statut</th>
                                            {{-- <th>Actions</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($empruntsHistorique as $emprunt)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center ">
                                                        <img src="{{ asset('assets/img/' . $emprunt->ouvrage->photo) }}"
                                                            width="50" height="70" class="rounded me-5"
                                                            style="object-fit: cover;">
                                                        <div class="flex-grow-1 ">
                                                            <strong>{{ $emprunt->ouvrage->titre }}</strong><br>
                                                            <small
                                                                class="text-muted">{{ $emprunt->ouvrage->auteur }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                                <td>{{ $emprunt->date_retour->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $emprunt->statut == 'retourne' ? 'success' : 'warning' }}">
                                                        {{ $emprunt->statut == 'retourne' ? 'Retourné' : 'En retard' }}
                                                    </span>
                                                </td>
                                                {{-- <td>
                                                    <a href="{{ url('frontOffice.ouvrages.show', $emprunt->ouvrage->id) }}"
                                                        class="btn btn-sm btn-outline-secondary" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $empruntsHistorique->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS personnalisé -->
    <style>
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background-color: transparent;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
    </style>

@endsection
