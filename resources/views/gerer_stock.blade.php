<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock - Bibliothèque</title>
    <!-- CSS -->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-light-info {
            background-color: rgba(23, 162, 184, 0.1) !important;
        }

        .book-cover {
            width: 40px;
            height: 55px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #eee;
        }

        .book-cover-lg {
            width: 160px;
            height: 220px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .action-buttons .btn {
            margin: 0 2px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .table-responsive {
            padding: 0 15px;
        }

        .card-header h1 {
            font-size: 1.8rem;
            padding: 15px 0;
        }

        .stock-preview {
            cursor: pointer;
            transition: color 0.2s;
            font-weight: 500;
        }

        .stock-preview:hover {
            color: #0d6efd;
            text-decoration: underline;
        }

        .status-badge {
            min-width: 100px;
            display: inline-block;
            text-align: center;
        }

        /* Styles spécifiques pour les statuts de stock */
        .badge-en-stock {
            background-color: #28a745;
        }

        .badge-stock-faible {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-rupture {
            background-color: #dc3545;
        }

        /* Style pour les cartes de statistiques */
        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
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
                            <h1 class="mt-2"><i class="fas fa-boxes mr-2"></i>Gestion du Stock</h1>
                            <a href="{{ url('stocks.create') }}" class="btn btn-light">
                                <i class="fas fa-plus mr-1"></i> Ajouter un Stock
                            </a>
                        </div>
                    </div>

                    <!-- Cartes de statistiques -->
                    <div class="row mb-4">
                        <!-- Carte Total Stock -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-primary">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-primary text-white mb-2">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                    <h3 class="stat-value mb-1">{{ $totalStock ?? '0' }}</h3>
                                    <p class="text-muted mb-0">Total en Stock</p>
                                    <div class="text-xs text-primary mt-1">
                                        <i class="fas fa-calendar-day"></i> {{ now()->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte En Stock -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-success">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-success text-white mb-2">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="stat-value mb-1">{{ $stats['en_stock'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0">En Stock</p>
                                    <div class="text-xs text-success mt-1">
                                        {{ $totalStock > 0 ? round(($stats['en_stock'] / $totalStock) * 100, 1) : 0 }}%
                                        du total
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Stock Faible -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-warning">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-warning text-white mb-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <h3 class="stat-value mb-1">{{ $stats['stock_faible'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0">Stock Faible</p>
                                    <div class="text-xs text-warning mt-1">
                                        <i class="fas fa-exclamation-circle"></i> À réapprovisionner
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Rupture -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card border-0 bg-light-danger">
                                <div class="card-body text-center p-3">
                                    <div class="stat-icon bg-danger text-white mb-2">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <h3 class="stat-value mb-1">{{ $stats['rupture'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0">En Rupture</p>
                                    <div class="text-xs text-danger mt-1">
                                        <i class="fas fa-clock"></i> Réapprovisionnement urgent
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message de succès -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Tableau des stocks -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des stocks</h5>
                            <div class="d-flex">
                                <!-- Filtre par statut -->
                                <div class="btn-group btn-group-toggle mr-2" data-toggle="buttons">
                                    <label
                                        class="btn btn-outline-primary btn-sm {{ $current_filter === 'all' ? 'active' : '' }}"
                                        data-filter="all">
                                        <input type="radio" name="status-filter"
                                            {{ $current_filter === 'all' ? 'checked' : '' }}> Tous
                                    </label>
                                    <label
                                        class="btn btn-outline-success btn-sm {{ $current_filter === 'En stock' ? 'active' : '' }}"
                                        data-filter="En stock">
                                        <input type="radio" name="status-filter"
                                            {{ $current_filter === 'En stock' ? 'checked' : '' }}> En Stock
                                    </label>
                                    <label
                                        class="btn btn-outline-warning btn-sm {{ $current_filter === 'Stock faible' ? 'active' : '' }}"
                                        data-filter="Stock faible">
                                        <input type="radio" name="status-filter"
                                            {{ $current_filter === 'Stock faible' ? 'checked' : '' }}> Stock Faible
                                    </label>
                                    <label
                                        class="btn btn-outline-danger btn-sm {{ $current_filter === 'Rupture' ? 'active' : '' }}"
                                        data-filter="Rupture">
                                        <input type="radio" name="status-filter"
                                            {{ $current_filter === 'Rupture' ? 'checked' : '' }}> Rupture
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="stocksTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Couverture</th>
                                            <th>Titre</th>
                                            <th class="text-right">Quantité</th>
                                            <th class="text-right">Prix Achat</th>
                                            <th class="text-right">Prix Vente</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stocks as $stock)
                                            <tr data-status="{{ $stock->statut }}">
                                                <td>STK-{{ str_pad($stock->id, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td>
                                                    <img src="{{ optional($stock->ouvrage)->photo ? asset('assets/img/' . $stock->ouvrage->photo) : asset('assets/img/no-cover.jpg') }}"
                                                        alt="Couverture" class="book-cover">
                                                </td>
                                                <td>
                                                    <a href="#" class="stock-preview" data-toggle="modal"
                                                        data-target="#viewStockModal-{{ $stock->id }}">
                                                        {{ optional($stock->ouvrage)->titre ?? '—' }}
                                                    </a>
                                                </td>
                                                <td class="text-right">{{ $stock->quantite }}</td>
                                                <td class="text-right">{{ number_format($stock->prix_achat, 2) }} $
                                                </td>
                                                <td class="text-right">{{ number_format($stock->prix_vente, 2) }} $
                                                </td>
                                                <td>
                                                    @php
                                                        $statutClass =
                                                            [
                                                                'En stock' => 'badge-en-stock',
                                                                'Stock faible' => 'badge-stock-faible',
                                                                'Rupture' => 'badge-rupture',
                                                            ][$stock->statut] ?? 'badge-secondary';

                                                        $statutIcon =
                                                            [
                                                                'En stock' => 'fa-check-circle',
                                                                'Stock faible' => 'fa-exclamation-triangle',
                                                                'Rupture' => 'fa-times-circle',
                                                            ][$stock->statut] ?? 'fa-question-circle';
                                                    @endphp
                                                    <span class="badge status-badge {{ $statutClass }}">
                                                        <i class="fas {{ $statutIcon }} mr-1"></i>
                                                        {{ $stock->statut }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- Bouton Voir détails -->
                                                        <button type="button" class="btn btn-outline-info mx-1"
                                                            title="Voir détails" data-toggle="modal"
                                                            data-target="#viewStockModal-{{ $stock->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Bouton Modifier -->
                                                        <a href="{{ url('stocks.edit', $stock->id) }}"
                                                            class="btn btn-outline-primary mx-1" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
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
                                Affichage de <b>{{ $stocks->firstItem() }}</b> à
                                <b>{{ $stocks->lastItem() }}</b> sur <b>{{ $stocks->total() }}</b> éléments
                            </div>
                            <div>
                                {{ $stocks->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals pour chaque stock -->
    @foreach ($stocks as $stock)
        <!-- Modal Détails Stock -->
        <div class="modal fade" id="viewStockModal-{{ $stock->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-box-open mr-2"></i>
                            Détails du Stock - {{ optional($stock->ouvrage)->titre ?? 'N/A' }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Section Détails Stock -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Détails du Stock
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            @if (optional($stock->ouvrage)->photo)
                                                <img src="{{ asset('assets/img/' . $stock->ouvrage->photo) }}"
                                                    class="book-cover-lg mb-2" alt="Couverture">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                    style="height: 220px; width: 160px; margin: 0 auto;">
                                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-book mr-2 text-primary"></i>Ouvrage</span>
                                                <span>{{ optional($stock->ouvrage)->titre ?? '—' }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-hashtag mr-2 text-secondary"></i>ID Stock</span>
                                                <span>STK-{{ str_pad($stock->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-boxes mr-2 text-info"></i>Quantité</span>
                                                <span>{{ $stock->quantite }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Prix et Statut -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Informations
                                            Financières</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6 class="mb-3"><i class="fas fa-exchange-alt mr-2"></i>Statut</h6>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $statutClass =
                                                        [
                                                            'En stock' => 'badge-en-stock',
                                                            'Stock faible' => 'badge-stock-faible',
                                                            'Rupture' => 'badge-rupture',
                                                        ][$stock->statut] ?? 'badge-secondary';

                                                    $statutIcon =
                                                        [
                                                            'En stock' => 'fa-check-circle',
                                                            'Stock faible' => 'fa-exclamation-triangle',
                                                            'Rupture' => 'fa-times-circle',
                                                        ][$stock->statut] ?? 'fa-question-circle';
                                                @endphp
                                                <span class="badge status-badge {{ $statutClass }}"
                                                    style="font-size: 1rem;">
                                                    <i class="fas {{ $statutIcon }} mr-1"></i>
                                                    {{ $stock->statut }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="mb-3"><i class="fas fa-money-bill-wave mr-2"></i>Prix d'Achat
                                            </h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span
                                                    class="font-weight-bold">{{ number_format($stock->prix_achat, 2) }}
                                                    $</span>
                                                <small class="text-muted">Unitaire</small>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="mb-3"><i class="fas fa-tag mr-2"></i>Prix de Vente</h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span
                                                    class="font-weight-bold">{{ number_format($stock->prix_vente, 2) }}
                                                    $</span>
                                                <small class="text-muted">Unitaire</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Calcul des marges -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-calculator mr-2"></i>Calcul des Marges
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <h6>Marge Unitaire</h6>
                                                <h4 class="text-success">
                                                    {{ number_format($stock->prix_vente - $stock->prix_achat, 2) }} $
                                                </h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Marge Totale</h6>
                                                <h4 class="text-primary">
                                                    {{ number_format(($stock->prix_vente - $stock->prix_achat) * $stock->quantite, 2) }}
                                                    $</h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Pourcentage</h6>
                                                <h4 class="text-info">
                                                    {{-- {{ $stock->prix_achat > 0 ? round((($stock->prix_vente - $stock->prix_achat) / $stock->prix_achat * 100, 2) : 0 }}% --}}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Fermer
                        </button>
                        <a href="{{ url('stocks.edit', $stock->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
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
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    {{-- <script>
        $(document).ready(function() {
            // Initialisation DataTables
            const table = $('#stocksTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { orderable: false, targets: [7] } // Désactiver le tri sur la colonne Actions
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
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            // Initialisation DataTables sans pagination ni recherche
            const table = $('#stocksTable').DataTable({
                paging: false, // Désactive la pagination DataTables
                info: false, // Cache "Showing 1 to X of Y entries"
                searching: false, // Désactive la recherche DataTables
                ordering: true, // Permet le tri manuel des colonnes
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                        orderable: false,
                        targets: [7]
                    } // Désactiver le tri sur la colonne Actions
                ]
            });

            // Filtre par statut - Version avec rechargement page
            $('[data-filter]').on('click', function() {
                const filter = $(this).data('filter');
                const url = new URL(window.location.href);

                if (filter === 'all') {
                    url.searchParams.delete('statut');
                } else {
                    url.searchParams.set('statut', filter);
                }

                window.location.href = url.toString();
            });

            // Marquer le filtre actif
            const currentFilter = "{{ $current_filter }}";
            if (currentFilter !== 'all') {
                $(`[data-filter="${currentFilter}"]`).addClass('active').find('input').prop('checked', true);
                $(`[data-filter="all"]`).removeClass('active').find('input').prop('checked', false);
            }
        });
    </script>
</body>

</html>
