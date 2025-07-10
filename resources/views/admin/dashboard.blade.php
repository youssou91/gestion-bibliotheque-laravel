<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emprunts</title>
    <!-- CSS -->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .stat-card {
            transition: transform 0.2s;
            border-radius: 10px;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .bg-light-primary {
            background-color: rgba(78, 115, 223, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(28, 200, 138, 0.1) !important;
        }

        .bg-light-warning {
            background-color: rgba(246, 194, 62, 0.1) !important;
        }

        .bg-light-danger {
            background-color: rgba(231, 74, 59, 0.1) !important;
        }

        .card-header h5 {
            margin-bottom: 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .badge-retourne {
            background-color: #1cc88a;
        }

        .badge-en_cours {
            background-color: #4e73df;
        }

        .badge-en_retard {
            background-color: #e64a3b;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <!-- En-tête -->
                    <div class="card-header bg-primary text-white mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="mt-2"><i class="fas fa-book-open mr-2"></i>Gestion des Emprunts</h1>
                        </div>
                    </div>

                    <!-- Cartes de statistiques -->
                    <div class="row mb-4">
                        <!-- Carte Emprunts du mois -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-primary">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-primary text-white mb-2">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <h3 class="mb-1">{{ $empruntsMois }}</h3>
                                    <p class="text-muted mb-0">Emprunts du mois</p>
                                    <div class="text-xs text-primary mt-1">
                                        <i class="fas fa-calendar-week"></i> {{ $empruntsSemaine }} cette semaine
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Retards -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-danger">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-danger text-white mb-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <h3 class="mb-1">{{ $retards }}</h3>
                                    <p class="text-muted mb-0">Retards</p>
                                    <div class="text-xs text-danger mt-1">
                                        <i class="fas fa-clock"></i> À traiter
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Emprunts en cours -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-info">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-info text-white mb-2">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <h3 class="mb-1">{{ $empruntsEnCours }}</h3>
                                    <p class="text-muted mb-0">En cours</p>
                                    <div class="text-xs text-info mt-1">
                                        <i class="fas fa-chart-line"></i> Actifs
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Top Ouvrage -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-warning">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-warning text-white mb-2">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-1" title="{{ $topOuvrage }}">
                                        {{ Str::words($topOuvrage, 2, '...') }}
                                    </h4>
                                    <p class="text-muted mb-0">Top ouvrage</p>
                                    <div class="text-xs text-warning mt-1">
                                        <i class="fas fa-book-reader"></i> {{ $topEmprunts }} emprunts
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graphiques -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Évolution des emprunts (30 jours)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="empruntsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Répartition par statut</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="statutChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des emprunts -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Derniers emprunts</h5>
                            <div class="d-flex">
                                <!-- Filtre par statut -->
                                <div class="btn-group btn-group-toggle mr-2" data-toggle="buttons">
                                    <label class="btn btn-outline-primary btn-sm active" data-filter="all">
                                        <input type="radio" name="status-filter" checked> Tous
                                    </label>
                                    <label class="btn btn-outline-info btn-sm" data-filter="en_cours">
                                        <input type="radio" name="status-filter"> En cours
                                    </label>
                                    <label class="btn btn-outline-success btn-sm" data-filter="retourne">
                                        <input type="radio" name="status-filter"> Retournés
                                    </label>
                                    <label class="btn btn-outline-danger btn-sm" data-filter="en_retard">
                                        <input type="radio" name="status-filter"> Retards
                                    </label>
                                </div>
                                <!-- Filtre par date -->
                                <input type="text" id="date-range-picker"
                                    class="form-control form-control-sm datepicker" placeholder="Filtrer par date"
                                    style="width: 180px;">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="empruntsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Date emprunt</th>
                                            <th>Utilisateur</th>
                                            <th>Ouvrage</th>
                                            <th>Statut</th>
                                            <th>Retour prévu</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lastEmprunts as $emprunt)
                                            <tr data-status="{{ $emprunt->statut == 'retourne' ? 'retourne' : ($emprunt->date_retour < now() ? 'en_retard' : 'en_cours') }}"
                                                data-date="{{ $emprunt->created_at->format('Y-m-d') }}">
                                                <td>#EMP-{{ str_pad($emprunt->id, 4, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $emprunt->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                                            style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                            {{ substr($emprunt->utilisateur->prenom ?? '?', 0, 1) }}{{ substr($emprunt->utilisateur->nom ?? '?', 0, 1) }}
                                                        </div>
                                                        <span>
                                                            {{ $emprunt->utilisateur->prenom ?? 'Inconnu' }}
                                                            {{ $emprunt->utilisateur->nom ?? '' }}
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $emprunt->utilisateur->email ?? '' }}</small>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{-- {{ Str::limit($emprunt->ouvrage->titre ?? 'Inconnu', 30) }} --}}
                                                    {{ strlen($emprunt->ouvrage->titre) > 30 ? substr($emprunt->ouvrage->titre, 0, 30) . '...' : $emprunt->ouvrage->titre }}
                                                    @if ($emprunt->ouvrage && $emprunt->ouvrage->isbn)
                                                        <br><small class="text-muted">ISBN:
                                                            {{ $emprunt->ouvrage->isbn }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($emprunt->statut == 'retourne')
                                                        <span class="badge badge-retourne">Retourné</span>
                                                    @elseif($emprunt->date_retour < now())
                                                        <span class="badge badge-en_retard">En retard</span>
                                                    @else
                                                        <span class="badge badge-en_cours">En cours</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $emprunt->date_retour->format('d/m/Y') }}
                                                    @if ($emprunt->date_retour < now() && $emprunt->statut != 'retourne')
                                                        <br><small
                                                            class="text-danger">+{{ now()->diffInDays($emprunt->date_retour) }}j</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- Bouton Voir détails -->
                                                        <button type="button"
                                                            class="btn btn-outline-info btn-sm mx-1"
                                                            title="Voir détails" data-toggle="modal"
                                                            data-target="#viewEmpruntModal-{{ $emprunt->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Affichage de <b>{{ $lastEmprunts->firstItem() }}</b> à
                                <b>{{ $lastEmprunts->lastItem() }}</b> sur <b>{{ $lastEmprunts->total() }}</b>
                                emprunts
                            </div>
                            <div>
                                {{ $lastEmprunts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- Modals pour chaque emprunt -->
    @foreach ($lastEmprunts as $emprunt)
        <!-- Modal Détails Emprunt -->
        <div class="modal fade" id="viewEmpruntModal-{{ $emprunt->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-book-open mr-2"></i>
                            Emprunt #EMP-{{ str_pad($emprunt->id, 4, '0', STR_PAD_LEFT) }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Section Utilisateur -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Utilisateur</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; font-size: 1.2rem;">
                                                {{ substr($emprunt->utilisateur->prenom ?? '?', 0, 1) }}{{ substr($emprunt->utilisateur->nom ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <h5 class="mb-1">
                                                    {{ $emprunt->utilisateur->prenom ?? 'Inconnu' }}
                                                    {{ $emprunt->utilisateur->nom ?? '' }}
                                                </h5>
                                                <p class="text-muted mb-1">
                                                    {{ $emprunt->utilisateur->email ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-phone mr-2"></i>Téléphone</span>
                                                <span>{{ $emprunt->utilisateur->telephone ?? 'Non renseigné' }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-alt mr-2"></i>Membre depuis</span>
                                                <span>{{ $emprunt->utilisateur->created_at->format('d/m/Y') ?? 'Inconnu' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Emprunt -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Détails Emprunt</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-day mr-2"></i>Date emprunt</span>
                                                <span>{{ $emprunt->created_at->format('d/m/Y') }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-clock mr-2"></i>Statut</span>
                                                <span
                                                    class="badge badge-{{ $emprunt->statut == 'retourne' ? 'success' : ($emprunt->date_retour < now() ? 'danger' : 'info') }}">
                                                    {{ $emprunt->statut == 'retourne' ? 'Retourné' : ($emprunt->date_retour < now() ? 'en_retard' : 'en_cours') }}
                                                </span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-check mr-2"></i>Retour prévu</span>
                                                <span>{{ $emprunt->date_retour->format('d/m/Y') }}</span>
                                            </li>
                                            @if ($emprunt->statut == 'retourne')
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-calendar-times mr-2"></i>Date retour</span>
                                                    <span>{{ $emprunt->updated_at->format('d/m/Y H:i') }}</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Ouvrage -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Ouvrage emprunté</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                @if ($emprunt->ouvrage && $emprunt->ouvrage->photo)
                                                    <img src="{{ asset('assets/img/' . $emprunt->ouvrage->photo) }}"
                                                        class="img-fluid rounded" alt="Couverture de l'ouvrage">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                        style="height: 150px; width: 100%;">
                                                        <i class="fas fa-book-open fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-9">
                                                <h4>
                                                    {{ strlen($emprunt->ouvrage->titre) > 30 ? substr($emprunt->ouvrage->titre, 0, 30) . '...' : $emprunt->ouvrage->titre }}
                                                </h4>
                                                <p class="text-muted">
                                                    {{ $emprunt->ouvrage->auteur ?? 'Auteur inconnu' }}</p>

                                                <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>ISBN:</strong></p>
                                                        <p>{{ $emprunt->ouvrage->isbn ?? 'Non renseigné' }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Année:</strong></p>
                                                        <p>{{ $emprunt->ouvrage->annee_publication ?? 'Non renseigné' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Disponibilité:</strong></p>
                                                        <p>{{ $emprunt->ouvrage->stock->quantite ?? '0' }} exemplaire(s)
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Retour Emprunt -->
        <div class="modal fade" id="returnEmpruntModal-{{ $emprunt->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Enregistrer retour</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Confirmer le retour de l'ouvrage
                            <strong>
                                {{-- {{ $emprunt->ouvrage->titre ?? 'Inconnu' }} --}}
                                {{ strlen($emprunt->ouvrage->titre) > 30 ? substr($emprunt->ouvrage->titre, 0, 30) . '...' : $emprunt->ouvrage->titre }}
                            </strong>
                            ?
                        </p>
                        <p>Emprunté par : <strong>{{ $emprunt->utilisateur->prenom ?? 'Inconnu' }}
                                {{ $emprunt->utilisateur->nom ?? '' }}</strong></p>

                        @if ($emprunt->date_retour < now())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Cet emprunt est en retard de {{ now()->diffInDays($emprunt->date_retour) }} jour(s)
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <form action="{{ url('emprunts.retourner', $emprunt->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check mr-1"></i> Confirmer retour
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Prolonger Emprunt -->
        <div class="modal fade" id="extendEmpruntModal-{{ $emprunt->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Prolonger l'emprunt</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Prolonger l'emprunt de <strong>
                                {{-- {{ $emprunt->ouvrage->titre ?? 'Inconnu' }} --}}
                                {{ strlen($emprunt->ouvrage->titre) > 30 ? substr($emprunt->ouvrage->titre, 0, 30) . '...' : $emprunt->ouvrage->titre }}
                            </strong> ?</p>
                        <p>Emprunté par : <strong>{{ $emprunt->utilisateur->prenom ?? 'Inconnu' }}
                                {{ $emprunt->utilisateur->nom ?? '' }}</strong></p>

                        <div class="form-group">
                            <label for="newDate-{{ $emprunt->id }}">Nouvelle date de retour</label>
                            <input type="text" class="form-control datepicker" id="newDate-{{ $emprunt->id }}"
                                value="{{ now()->addWeeks(2)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <form action="{{ url('emprunts.prolonger', $emprunt->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <input type="hidden" name="new_date" id="newDateInput-{{ $emprunt->id }}">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-calendar-plus mr-1"></i> Prolonger
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Scripts -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialisation des datepickers
            $('.datepicker').flatpickr({
                dateFormat: "Y-m-d",
                locale: "fr"
            });

            // Initialisation des datepickers pour les modals de prolongation
            @foreach ($lastEmprunts as $emprunt)
                $('#newDate-{{ $emprunt->id }}').flatpickr({
                    dateFormat: "Y-m-d",
                    minDate: "{{ $emprunt->date_retour->format('Y-m-d') }}",
                    locale: "fr",
                    onChange: function(selectedDates, dateStr, instance) {
                        $('#newDateInput-{{ $emprunt->id }}').val(dateStr);
                    }
                });
                // Définir la valeur par défaut
                $('#newDateInput-{{ $emprunt->id }}').val("{{ now()->addWeeks(2)->format('Y-m-d') }}");
            @endforeach

            // Initialisation des graphiques
            const ctx1 = document.getElementById('empruntsChart').getContext('2d');
            const empruntsChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($dailyDates),
                    datasets: [{
                        label: 'Emprunts quotidiens',
                        data: @json($dailyData),
                        tension: 0.4,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const ctx2 = document.getElementById('statutChart').getContext('2d');
            const statutChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($statutLabels),
                    datasets: [{
                        data: @json($statutData),
                        backgroundColor: ['#4e73df', '#1cc88a', '#e64a3b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map(function(label, i) {
                                            return {
                                                text: label + ': ' + data.datasets[0].data[i],
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            $(document).ready(function() {
                // Initialisation DataTable avec configuration correcte
                // const table = $('#empruntsTable').DataTable({
                //     dom: '<"top"f>rt<"bottom"lip><"clear">',
                //     language: {
                //         url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                //     },
                //     columnDefs: [{
                //             orderable: false,
                //             targets: [6]
                //         }, // Désactiver le tri sur la colonne Actions
                //         {
                //             targets: [4],
                //             visible: false
                //         } // Masquer la colonne statut (utilisée pour le filtrage)
                //     ],
                //     processing: true,
                //     serverSide: true,
                //     ajax: {
                //         url: "{{ url('admin.emprunts.datatable') }}",
                //         type: 'GET'
                //     }
                // });

                // Filtre par statut
                $('[data-filter]').on('click', function() {
                    $('[data-filter]').removeClass('active');
                    $(this).addClass('active');

                    const filter = $(this).data('filter');
                    table.column(4).search(filter).draw(); // Colonne statut est l'index 4
                });

                // Filtre par date
                $('#date-range-picker').on('change', function() {
                    const dates = $(this).val().split(' to ');
                    if (dates.length === 2) {
                        table.column(1).search(dates[0] + '|' + dates[1])
                            .draw(); // Colonne date est l'index 1
                    } else {
                        table.column(1).search('').draw();
                    }
                });
            });



        });

        $(document).ready(function() {
            // Initialisation simple de DataTables sans pagination serveur
            const table = $('#empruntsTable').DataTable({
                paging: false, // Désactive la pagination DataTables
                info: false, // Désactive l'affichage du nombre de résultats
                searching: false, // Désactive la recherche globale
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                }
            });

            // Filtre par statut ultra-simplifié
            $('[data-filter]').on('click', function() {
                const filter = $(this).data('filter');

                // Active/désactive le style du bouton
                $('[data-filter]').removeClass('active');
                $(this).addClass('active');

                if (filter === 'all') {
                    // Affiche toutes les lignes
                    table.$('tr').show();
                } else {
                    // Cache tout puis affiche seulement les lignes correspondantes
                    table.$('tr').hide();
                    table.$('tr[data-status="' + filter + '"]').show();
                }
            });

            // Filtre par date simplifié
            $('#date-range-picker').flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                locale: "fr",
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length === 2) {
                        const start = selectedDates[0];
                        const end = selectedDates[1];

                        table.$('tr').each(function() {
                            const rowDate = new Date($(this).data('date'));
                            $(this).toggle(rowDate >= start && rowDate <= end);
                        });
                    } else {
                        table.$('tr').show();
                    }
                }
            });
        });
    </script>
</body>

</html>
