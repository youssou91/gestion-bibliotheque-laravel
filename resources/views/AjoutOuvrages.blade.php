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
                        <div class="ibox-body">
                            <div class="ibox">
                                <div class="ibox-head">
                                    <div class="ibox-title">Ajout d'ouvrages</div>
                                    {{-- affichage du message  --}}
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                </div>
                                <div class="ibox-body">
                                    <form action="{{ route('ajout_ouvrage.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label>Titre</label>
                                                <input class="form-control" name="titre" type="text"
                                                    placeholder="Titre du livre" required>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Date de publication</label>
                                                <input class="form-control" name="annee_publication" type="number"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label>Auteur</label>
                                                <input class="form-control" name="auteur" type="text"
                                                    placeholder="Nom de l'auteur" required>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Éditeur</label>
                                                <input class="form-control" name="editeur" type="text"
                                                    placeholder="Nom de l'éditeur" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label>ISBN</label>
                                                <input class="form-control" name="isbn" type="text"
                                                    placeholder="ISBN du livre" required>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Prix</label>
                                                <input class="form-control" name="prix" type="number" step="0.01"
                                                    placeholder="Prix en euros" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label>Niveau</label>
                                                <select class="form-control" name="niveau" id="">
                                                    <option value="" disabled selected> -- Choisir un niveau -- </option>
                                                    <option value="chef">Chef</option>
                                                    <option value="amateur">Amateur</option>
                                                    <option value="débutant">Débutant</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label for="categorie_id">Catégorie</label>
                                                <select name="categorie_id" id="categorie_id" class="form-control" required>
                                                    <option value="" disabled selected> -- Choisir une catégorie -- </option>
                                                    @foreach ($categories as $categorie)
                                                        <option value="{{ $categorie->id }}">
                                                            {{ $categorie->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" rows="5" placeholder="Description du livre"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo de couverture</label>
                                            <input class="form-control" name="photo" type="file">

                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-default" type="submit">Ajouter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- SCRIPTS -->
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
</body>

</html>
