<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Amendes</title>
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

        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-light-info {
            background-color: rgba(23, 162, 184, 0.1) !important;
        }

        .badge-payee {
            background-color: #28a745;
        }

        .badge-impayee {
            background-color: #dc3545;
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
                            <h1 class="mt-2"><i class="fas fa-money-bill-wave mr-2"></i>Gestion des Amendes</h1>
                        </div>
                    </div>

                    <!-- Cartes de statistiques -->
                    <div class="row mb-4">
                        <!-- Carte Total Amendes -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-danger">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-danger text-white mb-2">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <h3 class="mb-1">{{ $stats['total'] }}</h3>
                                    <p class="text-muted mb-0">Total Amendes</p>
                                    <div class="text-xs text-danger mt-1">
                                        <i class="fas fa-calendar-day"></i> {{ now()->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Amendes Impayées -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-warning">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-warning text-white mb-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <h3 class="mb-1">{{ $stats['impayees'] }}</h3>
                                    <p class="text-muted mb-0">Impayées</p>
                                    <div class="text-xs text-warning mt-1">
                                        {{ $stats['total'] > 0 ? round(($stats['impayees'] / $stats['total']) * 100, 1) : 0 }}%
                                        du total
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Amendes Payées -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-success">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-success text-white mb-2">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="mb-1">{{ $stats['payees'] }}</h3>
                                    <p class="text-muted mb-0">Payées</p>
                                    <div class="text-xs text-success mt-1">
                                        <i class="fas fa-chart-line"></i> Taux de paiement
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Montant Total -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-info">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-info text-white mb-2">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <h3 class="mb-1">{{ number_format($stats['montant_total'], 2) }} $</h3>
                                    <p class="text-muted mb-0">Montant Total</p>
                                    <div class="text-xs text-info mt-1">
                                        <i class="fas fa-wallet"></i> Cumul des amendes
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des amendes -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des amendes</h5>
                            <div class="d-flex">
                                <!-- Filtre par statut -->
                                <div class="btn-group btn-group-toggle mr-2" data-toggle="buttons">
                                    <label class="btn btn-outline-primary btn-sm active" data-filter="all">
                                        <input type="radio" name="status-filter" checked> Tous
                                    </label>
                                    <label class="btn btn-outline-success btn-sm" data-filter="payee">
                                        <input type="radio" name="status-filter"> Payées
                                    </label>
                                    <label class="btn btn-outline-danger btn-sm" data-filter="impayee">
                                        <input type="radio" name="status-filter"> Impayées
                                    </label>
                                </div>
                                <!-- Filtre par date -->
                                {{-- <input type="text" id="date-range-picker" class="form-control form-control-sm datepicker" 
                                       placeholder="Filtrer par date" style="width: 180px;"> --}}
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="amendesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Utilisateur</th>
                                            <th>Ouvrage</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($amendes as $amende)
                                            <tr data-status="{{ $amende->est_payee ? 'payee' : 'impayee' }}"
                                                data-date="{{ $amende->created_at->format('Y-m-d') }}">
                                                <td>#{{ str_pad($amende->id, 4, '0', STR_PAD_LEFT) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                                            style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                            {{ substr($amende->utilisateur->prenom, 0, 1) }}{{ substr($amende->utilisateur->nom, 0, 1) }}
                                                        </div>
                                                        <span>
                                                            {{ $amende->utilisateur->prenom }} {{ $amende->utilisateur->nom }}
                                                            <br>
                                                            <small class="text-muted">{{ $amende->utilisateur->email }}</small>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ strlen($amende->ouvrage->titre) > 30 ? substr($amende->ouvrage->titre, 0, 30).'...' : $amende->ouvrage->titre }}
                                                    @if ($amende->ouvrage->isbn)
                                                        <br><small class="text-muted">ISBN: {{ $amende->ouvrage->isbn }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($amende->montant, 2) }} $</td>
                                                <td>
                                                    @if ($amende->est_payee)
                                                        <span class="badge badge-payee">Payée</span>
                                                    @else
                                                        <span class="badge badge-impayee">Impayée</span>
                                                    @endif
                                                </td>
                                                <td>{{ $amende->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- Bouton Voir détails -->
                                                        <button type="button" class="btn btn-outline-info mx-1"
                                                            title="Voir détails" data-toggle="modal"
                                                            data-target="#viewAmendeModal-{{ $amende->id }}">
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
                                Affichage de <b>{{ $amendes->firstItem() }}</b> à
                                <b>{{ $amendes->lastItem() }}</b> sur <b>{{ $amendes->total() }}</b> amendes
                            </div>
                            <div>
                                {{ $amendes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals pour chaque amende -->
    @foreach ($amendes as $amende)
    <!-- Modal Détails Amende -->
    <div class="modal fade" id="viewAmendeModal-{{ $amende->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Amende #{{ str_pad($amende->id, 4, '0', STR_PAD_LEFT) }}
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
                                            {{ substr($amende->utilisateur->prenom, 0, 1) }}{{ substr($amende->utilisateur->nom, 0, 1) }}
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $amende->utilisateur->prenom }} {{ $amende->utilisateur->nom }}</h5>
                                            <p class="text-muted mb-1">{{ $amende->utilisateur->email }}</p>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-phone mr-2"></i>Téléphone</span>
                                            <span>{{ $amende->utilisateur->telephone ?? 'Non renseigné' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar-alt mr-2"></i>Membre depuis</span>
                                            <span>{{ $amende->utilisateur->created_at->format('d/m/Y') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Section Amende -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i>Détails de l'Amende</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-dollar-sign mr-2"></i>Montant</span>
                                            <span>$ {{ number_format($amende->montant, 2) }} </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-info-circle mr-2"></i>Statut</span>
                                            <span class="badge badge-{{ $amende->est_payee ? 'success' : 'danger' }}">
                                                {{ $amende->est_payee ? 'Payée' : 'Impayée' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar-day mr-2"></i>Date d'émission</span>
                                            <span>{{ $amende->created_at->format('d/m/Y') }}</span>
                                        </li>
                                        @if($amende->est_payee)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar-check mr-2"></i>Date de paiement</span>
                                            <span>{{ $amende->updated_at->format('d/m/Y') }}</span>
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
                                    <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Ouvrage concerné</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if ($amende->ouvrage->photo)
                                                <img src="{{ asset('assets/img/' . $amende->ouvrage->photo) }}" 
                                                     class="img-fluid rounded" alt="Couverture de l'ouvrage">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                     style="height: 150px; width: 100%;">
                                                    <i class="fas fa-book-open fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <h4>{{ $amende->ouvrage->titre }}</h4>
                                            <p class="text-muted">{{ $amende->ouvrage->auteur }}</p>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>ISBN:</strong></p>
                                                    <p>{{ $amende->ouvrage->isbn ?? 'Non renseigné' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Année:</strong></p>
                                                    <p>{{ $amende->ouvrage->annee_publication ?? 'Non renseigné' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Disponibilité:</strong></p>
                                                    <p>{{ $amende->ouvrage->stock->quantite ?? '0' }} exemplaire(s)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Fermer
                    </button>
                    @if(!$amende->est_payee)
                        <form method="POST" action="{{ url('amendes.payer', $amende->id) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check mr-1"></i> Marquer comme payée
                            </button>
                        </form>
                    @endif
                </div> --}}
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
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialisation des datepickers
            $('.datepicker').flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                locale: "fr"
            });

            // Initialisation DataTables
            const table = $('#amendesTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { orderable: false, targets: [6] } // Désactiver le tri sur la colonne Actions
                ]
            });

            // Filtre par statut
            $('[data-filter]').on('click', function() {
                const filter = $(this).data('filter');
                
                if (filter === 'all') {
                    table.$('tr').show();
                } else {
                    table.$('tr').hide();
                    table.$('tr[data-status="' + filter + '"]').show();
                }
                
                updateCount();
            });

            // Filtre par date
            $('#date-range-picker').on('change', function() {
                const dates = $(this).val().split(' to ');
                
                if (dates.length === 2) {
                    const start = new Date(dates[0]);
                    const end = new Date(dates[1]);
                    
                    table.$('tr').each(function() {
                        const rowDate = new Date($(this).data('date'));
                        $(this).toggle(rowDate >= start && rowDate <= end);
                    });
                } else {
                    table.$('tr').show();
                }
                
                updateCount();
            });

            // Mise à jour du compteur
            function updateCount() {
                const visible = $('tbody tr:visible').length;
                const total = $('tbody tr').length;
                $('#count-display').html(`Affichage de <b>${visible}</b> sur <b>${total}</b> amendes`);
            }
        });
    </script>
</body>
</html>