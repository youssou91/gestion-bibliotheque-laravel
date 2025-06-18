<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Classification des Ouvrages</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ url('./assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .category-row {
            background-color: #e9f7ef;
        }

        .category-row:hover {
            background-color: #d4edda !important;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .table th {
            white-space: nowrap;
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
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <i class="fa fa-sitemap fa-lg mr-2"></i>
                            <h4 class="mb-0">Classification des Ouvrages</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-1"></i> Nouvelle Catégorie
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-info" data-toggle="modal" data-target="#statsModal">
                                            <i class="fa fa-bar-chart mr-1"></i> Statistiques
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card text-white bg-primary mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Catégories vides</h5>
                                            <p class="card-text display-4">{{ $stats['categoriesSansOuvrages'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Catégorie la plus riche</h5>
                                            <p class="card-text">
                                                <strong>{{ $stats['categorieLaPlusRiche']->nom }}</strong>
                                                avec {{ $stats['categorieLaPlusRiche']->ouvrages_count }} ouvrages
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="classification-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="35%">Catégorie</th>
                                            <th width="45%">Titres des ouvrages</th>
                                            <th width="10%" class="text-center">Nombre</th>
                                            <th width="20%" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category)
                                            <tr data-id="{{ $category->id }}" class="category-row">
                                                <td>
                                                    <i class="fa fa-folder-open text-warning mr-2"></i>
                                                    <strong>{{ $category->nom }}</strong>
                                                </td>
                                                <td>
                                                    @if ($category->ouvrages_count > 0)
                                                        <div class="d-flex flex-wrap">
                                                            @foreach ($category->ouvrages as $ouvrage)
                                                                <span class="badge badge-info mr-1 mb-1">
                                                                    {{ $ouvrage->titre }}
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
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-info btn-view mr-1"
                                                            data-toggle="modal" data-target="#viewModal"
                                                            data-id="{{ $category->id }}"
                                                            data-nom="{{ $category->nom }}"
                                                            data-count="{{ $category->ouvrages_count }}"
                                                            data-titres="{{ $category->ouvrages->pluck('titre')->join('||') }}">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <a href="{{ route('categories.edit', $category->id) }}"
                                                            class="btn btn-warning mr-1">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger"
                                                            data-toggle="modal" data-target="#deleteModal"
                                                            data-category-id="{{ $category->id }}"
                                                            data-category-name="{{ $category->nom }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    Aucune catégorie n'a été créée pour le moment.
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
                                            <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est
                                            irréversible.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annuler</button>
                                            <form id="deleteForm" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
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
                                            <h5 class="modal-title" id="viewModalLabel">Détails de la catégorie</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Fermer">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Nom :</strong> <span id="modal-nom"></span></p>
                                                    <p><strong>Nombre d'ouvrages :</strong> <span
                                                            id="modal-count"></span></p>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <span id="modal-id" class="badge badge-secondary"></span>
                                                </div>
                                            </div>
                                            <hr>
                                            <p><strong>Liste des ouvrages :</strong></p>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped" id="modal-titres-table">
                                                    <thead>
                                                        <tr>
                                                            <th width="80%">Titre</th>
                                                            <th width="20%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="modal-titres">
                                                        <!-- Contenu généré par JavaScript -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
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
                                            <h5 class="modal-title" id="statsModalLabel">Statistiques complètes</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Répartition par catégorie</h5>
                                                    <canvas id="repartitionChart" height="200"></canvas>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>Derniers ouvrages ajoutés</h5>
                                                    <ul class="list-group">
                                                        @foreach ($stats['derniersOuvrages'] as $ouvrage)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $ouvrage->titre }}
                                                                <span class="badge badge-primary badge-pill">
                                                                    {{ $ouvrage->categories->nom ?? 'Sans catégorie' }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <h5>Statistiques détaillées</h5>
                                                    <table class="table table-sm table-bordered">
                                                        <tr>
                                                            <th>Total catégories</th>
                                                            <td>{{ $stats['totalCategories'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Catégories sans ouvrages</th>
                                                            <td>{{ $stats['categoriesSansOuvrages'] }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('includes.footer')
            </div>
        </div>

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
                $('#classification-table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
                    },
                    "columnDefs": [{
                            "orderable": false,
                            "targets": [3]
                        },
                        {
                            "className": "dt-center",
                            "targets": [2, 3]
                        }
                    ]
                });

                $('.btn-view').on('click', function() {
                    let titles = $(this).data('titres').split('||');
                    $('#modal-nom').text($(this).data('nom'));
                    $('#modal-count').text($(this).data('count') + ' ouvrage(s)');
                    let $tbody = $('#modal-titres').empty();
                    if (titles.length === 1 && titles[0] === '') {
                        $tbody.append(
                            '<tr><td colspan="2" class="text-center text-muted">Aucun ouvrage dans cette catégorie</td></tr>'
                        );
                    } else {
                        titles.forEach(function(t) {
                            if (t.trim() !== '') {
                                $tbody.append(
                                    '<tr>' +
                                    '<td>' + t + '</td>' +
                                    '<td class="text-center">' +
                                    '</td>' +
                                    '</tr>'
                                );
                            }
                        });
                    }
                });

                var ctx = document.getElementById('repartitionChart').getContext('2d');
                var repartitionChart = new Chart(ctx, {
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
                        }
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#deleteModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Bouton qui a déclenché la modale
                    var categoryId = button.data('category-id');
                    var categoryName = button.data('category-name');

                    var modal = $(this);
                    modal.find('.modal-body').html('Êtes-vous sûr de vouloir supprimer la catégorie <strong>"' +
                        categoryName + '"</strong> ? Cette action est irréversible.');

                    // Mise à jour du formulaire avec la bonne URL
                    var form = modal.find('#deleteForm');
                    form.attr('action', '{{ url('categories') }}/' + categoryId);
                });
            });
        </script>
    </div>
</body>

</html>
