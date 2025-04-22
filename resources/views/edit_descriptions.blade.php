<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion de Bibliothèque – Ouvrages</title>
    <!-- Styles globaux -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet">
    <!-- CSS principal -->
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">

                    <!-- En-tête -->
                    <div class="card-header bg-info text-white mb-3">
                        <h1 class="mt-4"><i class="fa fa-book mr-2"></i>Gestion des Ouvrages</h1>
                    </div>

                    <!-- Ajouter + message -->
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <a href="{{ route('routeAjoutCat.getCategories') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Ajouter un Ouvrage
                        </a>
                        @if (session('success'))
                            <div class="alert alert-success mb-0">{{ session('success') }}</div>
                        @endif
                    </div>

                    <!-- Tableau ouvrages -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="livres-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:50px"></th>
                                            <th>Photo</th>
                                            <th>Titre</th>
                                            <th>Année</th>
                                            <th>Niveau</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($livres as $livre)
                                            <tr>
                                                <td class="text-center align-middle"><input type="checkbox"></td>
                                                <td class="align-middle">
                                                    <img src="{{ asset('assets/img/' . $livre->photo) }}" alt="Couverture"
                                                        class="rounded-circle"
                                                        style="width:35px; height:35px; object-fit:cover">
                                                </td>
                                                <td class="align-middle">{{ $livre->titre }}</td>
                                                <td class="align-middle">{{ $livre->annee_publication }}</td>
                                                <td class="align-middle">{{ $livre->niveau }}</td>
                                                <td class="text-center align-middle">
                                                    <a href="{{ route('routeModifierOuvrage.edit', $livre->id) }}"
                                                        class="btn btn-sm btn-primary" title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-success btn-view"
                                                        title="Voir" data-id="{{ $livre->id }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <form action="{{ route('routeSuppression.destroy', $livre->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Supprimer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- Modal de consultation (amélioré) -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-info">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fa fa-book-open mr-2"></i>Détails de l'Ouvrage</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="card border-0">
                                <img id="modal-cover" src="{{ asset('assets/img/placeholder.png') }}"
                                    class="card-img-top rounded shadow-sm" alt="Couverture">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-book mr-2 text-primary"></i>Titre</strong>
                                    <span id="modal-titre"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-calendar mr-2 text-secondary"></i>Année</strong>
                                    <span id="modal-annee"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-layer-group mr-2 text-secondary"></i>Niveau</strong>
                                    <span id="modal-niveau"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-list mr-2 text-success"></i>Catégorie</strong>
                                    <span id="modal-categorie"></span>
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fa fa-align-left mr-2 text-muted"></i>Description</strong>
                                    <p id="modal-description" class="mt-2 mb-0"></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-info" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/vendors/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
    <script>
        $(function() {
            $('#livres-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                        targets: [0, 1, 5],
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 1,
                        className: 'text-center'
                    }
                ]
            });

            const API_PREFIX = @json(url('/ouvrages'));
            const IMG_BASE_URL = @json(asset('assets/img'));
            const PLACEHOLDER_IMG = @json(asset('assets/img/placeholder.png'));

            $(document).on('click', '.btn-view', function() {
                const id = $(this).data('id');
                $.getJSON(`${API_PREFIX}/${id}`)
                    .done(data => {
                        // photo couverture
                        const cover = data.photo ?
                            `${IMG_BASE_URL}/${data.photo}` :
                            PLACEHOLDER_IMG;
                        $('#modal-cover').attr('src', cover);
                        $('#modal-titre').text(data.titre);
                        $('#modal-annee').text(data.annee_publication);
                        $('#modal-niveau').text(data.niveau);
                        $('#modal-categorie').text(data.categorie.nom);
                        $('#modal-description').text(data.description);
                        $('#viewModal').modal('show');
                    })
                    .fail((_, __, err) => {
                        console.error('AJAX error:', err);
                        alert('Impossible de charger les détails.');
                    });
            });
        });
    </script>
</body>

</html>
