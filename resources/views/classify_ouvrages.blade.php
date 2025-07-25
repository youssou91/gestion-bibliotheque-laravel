<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Classification des Ouvrages</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ url('./assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
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

        .category-row {
            background-color: #e9f7ef;
            transition: all 0.2s;
        }

        .category-row:hover {
            background-color: #d4edda !important;
            transform: translateX(2px);
        }

        .badge-category {
            font-size: 0.85em;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        .table th {
            white-space: nowrap;
            vertical-align: middle;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mt-4"><i class="fas fa-sitemap mr-2"></i>Classification des Ouvrages</h1>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i> Nouvelle Catégorie
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-info" data-toggle="modal" data-target="#statsModal">
                                    <i class="fas fa-chart-bar mr-2"></i> Statistiques
                                </button>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Carte Catégories vides -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-danger">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-danger text-white mb-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['categoriesSansOuvrages'] }}</h3>
                                        <p class="text-muted mb-0">Catégories vides</p>
                                        <div class="text-xs text-danger mt-1">
                                            {{ $stats['totalCategories'] > 0 ? round(($stats['categoriesSansOuvrages'] / $stats['totalCategories']) * 100, 1) : 0 }}%
                                            du total
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Catégorie la plus riche -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-success">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-success text-white mb-2">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['categorieLaPlusRiche']->ouvrages_count }}</h3>
                                        <p class="text-muted mb-0">Ouvrages dans
                                            {{ Str::limit($stats['categorieLaPlusRiche']->nom, 15) }}</p>
                                        <div class="text-xs text-success mt-1">
                                            <i class="fas fa-crown"></i> Catégorie la plus riche
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Total catégories -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-primary">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-primary text-white mb-2">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['totalCategories'] }}</h3>
                                        <p class="text-muted mb-0">Total Catégories</p>
                                        <div class="text-xs text-primary mt-1">
                                            <i class="fas fa-calendar-day"></i> {{ now()->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte Derniers ajouts -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-info">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-info text-white mb-2">
                                            <i class="fas fa-history"></i>
                                        </div>
                                        <h3 class="mb-1">{{ count($stats['derniersOuvrages']) }}</h3>
                                        <p class="text-muted mb-0">Derniers ouvrages</p>
                                        <div class="text-xs text-info mt-1">
                                            <i class="fas fa-clock"></i> 30 derniers jours
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="classification-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="35%">Catégorie</th>
                                        <th width="45%">Titres des ouvrages</th>
                                        <th width="10%" class="text-center">Nombre</th>
                                        <th width="10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr data-id="{{ $category->id }}" class="category-row">
                                            <td>
                                                <i class="fas fa-folder-open text-warning mr-2"></i>
                                                <strong>{{ $category->nom }}</strong>
                                            </td>
                                            <td>
                                                @if ($category->ouvrages_count > 0)
                                                    <div class="d-flex flex-wrap">
                                                        @foreach ($category->ouvrages as $ouvrage)
                                                            <span class="badge badge-info badge-category">
                                                                {{ strlen($ouvrage->titre) > 25 ? substr($ouvrage->titre, 0, 25) . '...' : $ouvrage->titre }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">Aucun ouvrage</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-pill badge-primary">
                                                    {{ $category->ouvrages_count }}
                                                </span>
                                            </td>
                                            <td class="text-center action-buttons">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Bouton Voir détails -->
                                                    <button type="button" class="btn btn-outline-info mx-1 btn-view"
                                                        title="Voir détails" data-toggle="modal"
                                                        data-target="#viewModal" data-id="{{ $category->id }}"
                                                        data-nom="{{ $category->nom }}"
                                                        data-count="{{ $category->ouvrages_count }}"
                                                        data-titres="{{ $category->ouvrages->pluck('titre')->join('||') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Bouton Modifier -->
                                                    <a href="{{ route('categories.edit', $category->id) }}"
                                                        class="btn btn-outline-warning mx-1" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Bouton Supprimer -->
                                                    <button type="button" class="btn btn-outline-danger mx-1"
                                                        title="Supprimer" data-toggle="modal"
                                                        data-target="#deleteModal"
                                                        data-category-id="{{ $category->id }}"
                                                        data-category-name="{{ $category->nom }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle mr-2"></i>Aucune catégorie n'a été créée
                                                pour le moment.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal de confirmation de suppression -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteModalLabel">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>Confirmer la suppression
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong
                                                id="categoryNameToDelete"></strong> ?</p>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            Cette action est irréversible et affectera les ouvrages associés.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times mr-1"></i> Annuler
                                        </button>
                                        <form id="deleteForm" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash mr-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de visualisation des détails -->
                        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog"
                            aria-labelledby="viewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="viewModalLabel">
                                            <i class="fas fa-folder-open mr-2"></i>Détails de la catégorie
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Fermer">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-4">
                                            <div class="col-md-8">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i
                                                                class="fas fa-info-circle mr-2"></i>Informations</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p>
                                                                    <strong>
                                                                        <i class="fas fa-tag mr-2"></i>
                                                                        Nom :
                                                                    </strong>
                                                                    <span id="modal-nom"
                                                                        class="font-weight-bold"></span>
                                                                </p>
                                                                <p>
                                                                    <strong>
                                                                        <i class="fas fa-hashtag mr-2"></i>
                                                                        ID :
                                                                    </strong>
                                                                    <span id="modal-id"
                                                                        class="badge badge-secondary"></span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p>
                                                                    <strong>
                                                                        <i class="fas fa-book mr-2"></i>
                                                                        Nombre d'ouvrages :
                                                                    </strong>
                                                                    <span id="modal-count"
                                                                        class="badge badge-primary"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i
                                                                class="fas fa-chart-pie mr-2"></i>Statistiques</h6>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <canvas id="miniChart" height="120"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="fas fa-book-open mr-2"></i>Ouvrages
                                                    associés</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th width="80%">Titre</th>
                                                                <th width="20%">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="modal-titres">
                                                            <!-- Le contenu sera rempli dynamiquement -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times mr-1"></i> Fermer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal des statistiques -->
                        <div class="modal fade" id="statsModal" tabindex="-1" role="dialog"
                            aria-labelledby="statsModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="statsModalLabel">
                                            <i class="fas fa-chart-bar mr-2"></i>Statistiques complètes
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i
                                                                class="fas fa-chart-pie mr-2"></i>Répartition par
                                                            catégorie</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="repartitionChart" height="200"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-clock mr-2"></i>Derniers
                                                            ouvrages ajoutés</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            @foreach ($stats['derniersOuvrages'] as $ouvrage)
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <span>
                                                                        <i class="fas fa-book text-primary mr-2"></i>
                                                                        {{ strlen($ouvrage->titre) > 25 ? substr($ouvrage->titre, 0, 25) . '...' : $ouvrage->titre }}
                                                                    </span>
                                                                    <span class="badge badge-primary badge-pill">
                                                                        {{ $ouvrage->categories->nom ?? 'Sans catégorie' }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i
                                                                class="fas fa-table mr-2"></i>Statistiques détaillées
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-sm table-bordered">
                                                            <tr class="bg-light">
                                                                <th width="50%">Métrique</th>
                                                                <th width="50%">Valeur</th>
                                                            </tr>
                                                            <tr>
                                                                <td><i class="fas fa-folder mr-2"></i>Total catégories
                                                                </td>
                                                                <td class="font-weight-bold">
                                                                    {{ $stats['totalCategories'] }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><i class="fas fa-folder-open mr-2"></i>Catégories
                                                                    sans ouvrages</td>
                                                                <td class="font-weight-bold">
                                                                    {{ $stats['categoriesSansOuvrages'] }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><i class="fas fa-trophy mr-2"></i>Catégorie la plus
                                                                    riche</td>
                                                                <td class="font-weight-bold">
                                                                    {{ $stats['categorieLaPlusRiche']->nom }}
                                                                    ({{ $stats['categorieLaPlusRiche']->ouvrages_count }}
                                                                    ouvrages)
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times mr-1"></i> Fermer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('includes.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialisation DataTables
            $('#classification-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
                },
                columnDefs: [{
                    targets: [2, 3],
                    className: 'dt-center'
                }],
                order: [
                    [0, 'asc']
                ]
            });

            // Gestion de la modal de suppression
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var categoryId = button.data('category-id');
                var categoryName = button.data('category-name');

                var modal = $(this);
                modal.find('#categoryNameToDelete').text(categoryName);
                modal.find('#deleteForm').attr('action', '{{ url('categories') }}/' + categoryId);
            });

            // Variables pour stocker les charts
            var miniChart = null;
            var repartitionChart = null;

            // Gestion de la modal de visualisation
            $('#viewModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var categoryId = button.data('id');
                var categoryName = button.data('nom');
                var ouvragesCount = button.data('count');
                var titres = button.data('titres').split('||');

                // Mise à jour des informations de base
                $('#modal-nom').text(categoryName);
                $('#modal-id').text(categoryId);
                $('#modal-count').text(ouvragesCount);

                // Remplissage du tableau des ouvrages
                let $tbody = $('#modal-titres').empty();
                if (titres.length === 1 && titres[0] === '') {
                    $tbody.append(
                        '<tr><td colspan="2" class="text-center text-muted py-3"><i class="fas fa-book-open mr-2"></i>Aucun ouvrage dans cette catégorie</td></tr>'
                    );
                } else {
                    titres.forEach(function(titre) {
                        if (titre.trim() !== '') {
                            $tbody.append(
                                '<tr>' +
                                '<td>' + titre + '</td>' +
                                '<td class="text-center">' +
                                '<a href="#" class="btn btn-sm btn-outline-primary" title="Voir ouvrage">' +
                                '<i class="fas fa-eye"></i>' +
                                '</a>' +
                                '</td>' +
                                '</tr>'
                            );
                        }
                    });
                }

                // Destruction du chart précédent s'il existe
                if (miniChart) {
                    miniChart.destroy();
                }

                // Création du nouveau chart
                var ctx = document.getElementById('miniChart').getContext('2d');
                miniChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Ouvrages', 'Vide'],
                        datasets: [{
                            data: [ouvragesCount, ouvragesCount > 0 ? 0 : 1],
                            backgroundColor: ['#4e73df', '#e74a3b'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'bottom',
                        },
                        cutoutPercentage: 70
                    }
                });
            });

            // Chart principal pour les statistiques
            var ctx = document.getElementById('repartitionChart').getContext('2d');
            repartitionChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($chartData['repartition']['labels']),
                    datasets: [{
                        data: @json($chartData['repartition']['data']),
                        backgroundColor: @json($chartData['repartition']['colors']),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'right',
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.labels[tooltipItem.index] || '';
                                var value = data.datasets[0].data[tooltipItem.index];
                                var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            });

            // Nettoyage des charts lorsque les modals sont fermées
            $('#viewModal, #statsModal').on('hidden.bs.modal', function() {
                if (miniChart) {
                    miniChart.destroy();
                    miniChart = null;
                }
                if (repartitionChart) {
                    repartitionChart.destroy();
                    repartitionChart = null;
                }
            });
        });
    </script>
</body>

</html>
