<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<style>
    .stat-card {
        transition: transform 0.2s;
        border-radius: 10px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .bg-light-info {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }
    
    .stock-rupture {
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    .stock-faible {
        background-color: rgba(255, 193, 7, 0.05);
    }
    
    .stock-normal {
        background-color: rgba(25, 135, 84, 0.05);
    }
</style>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mt-4"><i class="fas fa-boxes mr-2"></i>Gestion des Stocks</h1>
                        <button class="btn btn-light" data-toggle="modal" data-target="#addStockModal">
                            <i class="fas fa-plus mr-2"></i>Ajouter Stock
                        </button>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <!-- Carte Total Ouvrages -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-primary">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-primary text-white mb-2">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['total'] }}</h3>
                                        <p class="text-muted mb-0">Ouvrages en stock</p>
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
                                        <h3 class="mb-1">{{ $stats['en_stock'] }}</h3>
                                        <p class="text-muted mb-0">En Stock</p>
                                        <div class="text-xs text-success mt-1">
                                            {{ $stats['total'] > 0 ? round(($stats['en_stock'] / $stats['total']) * 100, 1) : 0 }}%
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
                                        <h3 class="mb-1">{{ $stats['stock_faible'] }}</h3>
                                        <p class="text-muted mb-0">Stock Faible</p>
                                        <div class="text-xs text-warning mt-1">
                                            {{ $stats['total'] > 0 ? round(($stats['stock_faible'] / $stats['total']) * 100, 1) : 0 }}%
                                            du total
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Rupture de Stock -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-danger">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-danger text-white mb-2">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['rupture'] }}</h3>
                                        <p class="text-muted mb-0">Rupture de Stock</p>
                                        <div class="text-xs text-danger mt-1">
                                            <i class="fas fa-chart-line"></i> Taux de rupture
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mt-4">
                            <div class="col-md-8">
                                <label for="status-filter">Filtrer par statut :</label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-primary active" data-filter="all">
                                        <input type="radio" name="status-filter" value="all" checked> Tous
                                    </label>
                                    <label class="btn btn-outline-success" data-filter="En stock">
                                        <input type="radio" name="status-filter" value="En stock"> En Stock
                                    </label>
                                    <label class="btn btn-outline-warning" data-filter="Stock faible">
                                        <input type="radio" name="status-filter" value="Stock faible"> Stock Faible
                                    </label>
                                    <label class="btn btn-outline-danger" data-filter="Rupture">
                                        <input type="radio" name="status-filter" value="Rupture"> Rupture
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="alert alert-info p-2 mb-0">
                                    <i class="fas fa-boxes mr-2"></i>
                                    <strong>Total en stock :</strong> {{ $stats['total_quantite'] }} unités
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des stocks -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="stocks-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Ouvrage</th>
                                        <th>Auteur</th>
                                        <th>Quantité</th>
                                        <th>Prix Achat</th>
                                        <th>Prix Vente</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $stock)
                                        @php
                                            $rowClass = '';
                                            if ($stock->statut == 'Rupture') {
                                                $rowClass = 'stock-rupture';
                                            } elseif ($stock->statut == 'Stock faible') {
                                                $rowClass = 'stock-faible';
                                            } else {
                                                $rowClass = 'stock-normal';
                                            }
                                            
                                            $statusClass = [
                                                'En stock' => 'success',
                                                'Stock faible' => 'warning',
                                                'Rupture' => 'danger',
                                            ][$stock->statut];
                                        @endphp
                                        <tr class="{{ $rowClass }}" data-status="{{ $stock->statut }}">
                                            <td class="text-center">#STK-{{ str_pad($stock->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($stock->ouvrage->photo)
                                                        <img src="{{ asset('assets/img/' . $stock->ouvrage->photo) }}" 
                                                             class="img-thumbnail mr-3" 
                                                             style="width: 40px; height: 60px; object-fit: cover;" 
                                                             alt="{{ $stock->ouvrage->titre }}">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center mr-3" 
                                                             style="width: 40px; height: 60px;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $stock->ouvrage->titre }}</strong><br>
                                                        <small class="text-muted">ISBN: {{ $stock->ouvrage->isbn ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $stock->ouvrage->auteur }}</td>
                                            <td class="text-center">{{ $stock->quantite }}</td>
                                            <td class="text-right">{{ number_format($stock->prix_achat, 2, ',', ' ') }} €</td>
                                            <td class="text-right">{{ number_format($stock->prix_vente, 2, ',', ' ') }} €</td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ $stock->statut }}
                                                </span>
                                            </td>
                                            <td class="text-center" style="width: 150px">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Bouton Voir détails -->
                                                    <button type="button" class="btn btn-outline-info mx-1"
                                                            title="Voir détails" data-toggle="modal"
                                                            data-target="#viewStockModal-{{ $stock->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    
                                                    <!-- Bouton Modifier -->
                                                    <button type="button" class="btn btn-outline-primary mx-1 edit-stock"
                                                            title="Modifier" data-toggle="modal" 
                                                            data-target="#editStockModal" data-id="{{ $stock->id }}"
                                                            data-quantite="{{ $stock->quantite }}"
                                                            data-prix_achat="{{ $stock->prix_achat }}"
                                                            data-prix_vente="{{ $stock->prix_vente }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <!-- Bouton Supprimer -->
                                                    <button type="button" class="btn btn-outline-danger mx-1 delete-stock"
                                                            title="Supprimer" data-id="{{ $stock->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Affichage de <b>{{ $stocks->firstItem() }}</b> à
                                <b>{{ $stocks->lastItem() }}</b> sur <b>{{ $stocks->total() }}</b> entrées de stock
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

    <!-- Modal Ajout Stock -->
    <div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addStockModalLabel">
                        <i class="fas fa-plus-circle mr-2"></i>Ajouter un nouveau stock
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('gestion.stocks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ouvrage_id">Ouvrage</label>
                                    <select class="form-control" id="ouvrage_id" name="ouvrage_id" required>
                                        <option value=""selected enableTime>Sélectionner un ouvrage</option>
                                        @foreach($ouvrages as $ouvrage)
                                            <option value="{{ $ouvrage->id }}">{{ $ouvrage->titre }} ({{ $ouvrage->auteur }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantite">Quantité</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prix_achat">Prix d'achat (€)</label>
                                    <input type="number" step="0.01" class="form-control" id="prix_achat" name="prix_achat" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prix_vente">Prix de vente (€)</label>
                                    <input type="number" step="0.01" class="form-control" id="prix_vente" name="prix_vente" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Édition Stock -->
    <div class="modal fade" id="editStockModal" tabindex="-1" role="dialog" aria-labelledby="editStockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editStockModalLabel">
                        <i class="fas fa-edit mr-2"></i>Modifier le stock
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editStockForm" method="POST" action="{{ route('gestion.stocks.update', ':id') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_quantite">Quantité</label>
                                    <input type="number" class="form-control" id="edit_quantite" name="quantite" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Statut</label>
                                    <input type="text" class="form-control" id="edit_statut" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_prix_achat">Prix d'achat (€)</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_prix_achat" name="prix_achat" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_prix_vente">Prix de vente (€)</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_prix_vente" name="prix_vente" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Suppression Stock -->
    <div class="modal fade" id="deleteStockModal" tabindex="-1" role="dialog" aria-labelledby="deleteStockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteStockModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteStockForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer cet élément de stock ?</p>
                        <p class="text-danger"><strong>Cette action est irréversible !</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt mr-1"></i> Supprimer
                        </button>
                    </div>
                </form>
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
                                                    class="book-cover-lg mb-2" alt="Couverture"
                                                    style="width: 150px; height: 200px;">
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

    <script>
        $(document).ready(function() {
            // Initialisation DataTables
            const table = $('#stocks-table').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                }
            });

            // Filtre personnalisé
            $('[data-filter]').on('click', function() {
                const filter = $(this).data('filter');
                
                if(filter === 'all') {
                    table.$('tr').show();
                } else {
                    table.$('tr').hide();
                    table.$('tr[data-status="' + filter + '"]').show();
                }
            });

            // Gestion de la modification
            $('.edit-stock').on('click', function() {
                const stockId = $(this).data('id');
                const quantite = $(this).data('quantite');
                const prixAchat = $(this).data('prix_achat');
                const prixVente = $(this).data('prix_vente');
                
                // Déterminer le statut
                let statut = 'En stock';
                if(quantite == 0) {
                    statut = 'Rupture';
                } else if(quantite <= 5) {
                    statut = 'Stock faible';
                }
                
                $('#editStockForm').attr('action', '/stocks/' + stockId);
                $('#edit_quantite').val(quantite);
                $('#edit_prix_achat').val(prixAchat);
                $('#edit_prix_vente').val(prixVente);
                $('#edit_statut').val(statut);
            });

            // Gestion de la suppression
            $('.delete-stock').on('click', function() {
                const stockId = $(this).data('id');
                $('#deleteStockForm').attr('action', '/stocks/' + stockId);
                $('#deleteStockModal').modal('show');
            });

            
        });
    </script>
</body>
</html>