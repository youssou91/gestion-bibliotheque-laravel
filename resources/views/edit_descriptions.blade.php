<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Ouvrages</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card-header bg-info text-white">
                        <h1 class="mt-4"><i class="fa fa-book mr-2"></i>Gestion des Ouvrages</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <a href="{{ route('routeAjoutCat.getCategories') }}" class="btn btn-primary float-left"><i
                                    class="fa fa-plus"></i> Ajouter un Ouvrage</a>
                        </div>
                        {{-- affichage du message  --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="livres-table"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="50px"></th>
                                            <th>Photo</th>
                                            <th>Titre</th>
                                            <th>Annee</th>
                                            <th>Niveau</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($livres as $livre)
                                            <tr>
                                                <td>
                                                    <label class="ui-checkbox">
                                                        <input type="checkbox">
                                                        <span class="input-span"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <img src="{{ url('assets/img/' . $livre->photo) }}" alt="Couverture"
                                                        style="width:35px; height:35px; border-radius:50%; object-fit:cover;" />
                                                </td>
                                                <td> {{ $livre->titre }} </td>
                                                <td> {{ $livre->annee_publication }} </td>
                                                <td> {{ $livre->niveau }} </td>
                                                <td>
                                                    <a href="{{ route('routeModifierOuvrage.edit', $livre->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    {{-- <button class="btn btn-sm btn-success btn-view"
                                                        data-id="{{ $livre->id }}" data-toggle="modal"
                                                        data-target="#viewModal">
                                                        <i class="fa fa-eye"></i>
                                                    </button> --}}
                                                    <button class="btn btn-sm btn-success btn-view"
                                                        data-id="{{ $livre->id }}" data-toggle="modal"
                                                        data-target="#viewModal">
                                                        <i class="fa fa-eye"></i>
                                                    </button>

                                                    <form action="{{ route('routeSuppression.destroy', $livre->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            data-toggle="tooltip" data-original-title="Delete">
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
    <!-- Modal -->
    <!-- Modal de consultation -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-book"></i> Détails de l'ouvrage</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modal-photo" src="" class="img-fluid rounded mb-3" alt="Couverture">
                        </div>
                        <div class="col-md-8">
                            <dl class="row">
                                <dt class="col-sm-4">Titre :</dt>
                                <dd class="col-sm-8" id="modal-titre"></dd>
                                <dt class="col-sm-4">Année :</dt>
                                <dd class="col-sm-8" id="modal-annee"></dd>
                                <dt class="col-sm-4">Niveau :</dt>
                                <dd class="col-sm-8" id="modal-niveau"></dd>
                                <dt class="col-sm-4">Catégorie :</dt>
                                <dd class="col-sm-8" id="modal-categorie"></dd>
                                <dt class="col-sm-4">Description :</dt>
                                <dd class="col-sm-8" id="modal-description"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->Titre
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#livres-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                        targets: 0,
                        width: '5%'
                    },
                    {
                        targets: 3,
                        type: 'num'
                    },
                    {
                        targets: 4,
                        orderable: false
                    },
                    {
                        targets: 5,
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        })
    </script>
    {{-- script_Modal --}}
    <script>
        // Préfixe généré côté Blade pour éviter les erreurs de chemin
        const API_PREFIX = "{{ url('ouvrage') }}"; // donnera par ex. "http://localhost:8000/ouvrage"

        $(document).on('click', '.btn-view', function() {
            const id = $(this).data('id');
            const url = `${API_PREFIX}/${id}`; // ex. "http://localhost:8000/ouvrage/6"

            $.getJSON(url)
                .done(function(data) {
                    // Remplissage du modal
                    $('#modal-photo')
                        .attr('src', `/assets/img/${data.photo}`);
                    $('#modal-titre').text(data.titre);
                    $('#modal-annee').text(data.annee_publication);
                    $('#modal-niveau').text(data.niveau);
                    $('#modal-categorie').text(data.categorie.nom);
                    $('#modal-description').text(data.description);
                })
                .fail(function() {
                    // En cas d’erreur, vous pouvez afficher un message dans le modal
                    $('#modal-body').html('<p class="text-danger">Impossible de charger les détails.</p>');
                });
        });
    </script>


</body>

</html>
