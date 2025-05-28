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
                                            <th>Auteur</th>
                                            <th>Éditeur</th>
                                            <th>Prix</th>
                                            <th>Niveau</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($livres as $livre)
                                            <tr>
                                                <td class="text-center align-middle"><input type="checkbox"></td>
                                                <td class="align-middle">
                                                    <img src="{{ $livre->photo ? asset('assets/img/' . $livre->photo) : asset('assets/img/placeholder.png') }}" alt="Couverture"
                                                        class="rounded-circle"
                                                        style="width:35px; height:35px; object-fit:cover">
                                                </td>
                                                <td class="align-middle" style="width:300px">{{ $livre->titre }}</td>
                                                <td class="align-middle" style="width:130px">{{ $livre->auteur }}</td>
                                                <td class="align-middle" style="width:100px">{{ $livre->editeur }}</td>
                                                <td class="align-middle" style="width:70px">{{ number_format($livre->prix, 2) }} $</td>
                                                <td class="align-middle" style="width:90px">{{ $livre->niveau }}</td>
                                                <td class="text-center align-middle">
                                             
                                                    <button type="button" class="btn btn-sm btn-info btn-view" title="Voir" data-id="{{ $livre->id }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('routeModifierOuvrage.edit', $livre->id) }}"
                                                        class="btn btn-sm btn-primary" title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('routeSupprimerOuvrage.destroy', $livre->id) }}"
                                                        method="POST" class="d-inline-block"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet ouvrage ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
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

                    <!-- Modal pour voir les détails -->
                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title">Détails de l'ouvrage</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center mb-4">
                                        <img id="modal-cover" src="" alt="Couverture" class="rounded-circle" style="width:100px; height:100px; object-fit:cover">
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-book mr-2 text-primary"></i>Titre</strong>
                                            <span id="modal-titre"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-user mr-2 text-primary"></i>Auteur</strong>
                                            <span id="modal-auteur"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-building mr-2 text-secondary"></i>Éditeur</strong>
                                            <span id="modal-editeur"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-calendar mr-2 text-info"></i>Date de publication</strong>
                                            <span id="modal-date"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-tag mr-2 text-warning"></i>Prix</strong>
                                            <span id="modal-prix"></span>
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
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-outline-info" data-dismiss="modal">
                                        <i class="fa fa-times mr-1"></i>Fermer
                                    </button>
                                </div>
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
                                    <strong><i class="fa fa-user mr-2 text-primary"></i>Auteur</strong>
                                    <span id="modal-auteur"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-building mr-2 text-secondary"></i>Éditeur</strong>
                                    <span id="modal-editeur"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-calendar mr-2 text-info"></i>Date de publication</strong>
                                    <span id="modal-date"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-tag mr-2 text-warning"></i>Prix</strong>
                                    <span id="modal-prix"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong><i class="fa fa-layer-group mr-2 text-secondary"></i>Niveau</strong>
                                    <span id="modal-niveau"></span>
                                </li>
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
    <script src="{{ url('assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/vendors/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
    <script>
        $(function() {
            // Constantes pour les URLs
            const IMG_BASE_URL = '{{ asset("assets/img") }}';
            const PLACEHOLDER_IMG = '{{ asset("assets/img/placeholder.png") }}';

            // Gestionnaire de clic pour le bouton de visualisation
            $(document).on('click', '.btn-view', function() {
                const id = $(this).data('id');
                console.log('Chargement des détails pour l\'ouvrage:', id);

                $.get(`/ouvrage/${id}`)
                    .done(function(data) {
                        console.log('Données reçues:', data);
                        
                        // Mise à jour de l'image
                        const cover = data.photo ? `${IMG_BASE_URL}/${data.photo}` : PLACEHOLDER_IMG;
                        $('#modal-cover').attr('src', cover);

                        // Mise à jour des informations
                        $('#modal-titre').text(data.titre || '');
                        $('#modal-auteur').text(data.auteur || '');
                        $('#modal-editeur').text(data.editeur || '');
                        $('#modal-date').text(data.annee_publication || '');
                        $('#modal-prix').text(new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(data.prix || 0));
                        $('#modal-niveau').text(data.niveau || '');
                        $('#modal-categorie').text(data.categorie ? data.categorie.nom : '');
                        $('#modal-description').text(data.description || '');

                        // Afficher le modal
                        $('#viewModal').modal('show');
                    })
                    .fail(function(xhr) {
                        console.error('Erreur AJAX:', xhr.status, xhr.responseText);
                        alert('Impossible de charger les détails de l\'ouvrage.');
                    });
            });

            // Configuration de DataTables
            $('#livres-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
                },
                columnDefs: [{
                    targets: [0, 1, 7], // Colonnes checkbox, photo et actions
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
</body>
</html>
