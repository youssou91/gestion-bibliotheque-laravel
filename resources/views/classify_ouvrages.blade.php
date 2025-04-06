<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Ouvrages</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css')}}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css')}}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css')}}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card-header bg-success text-white">
                        <h1 class="mt-4"><i class="fa fa-sitemap mr-2"></i>Classification des Ouvrages</h1>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i> Nouvelle Catégorie
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover" id="classification-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Catégorie</th>
                                            <th>Sous-catégories</th>
                                            <th>Nombre d'ouvrages</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-level="1">
                                            <td>
                                                <i class="fa fa-folder-open text-warning mr-2"></i>
                                                Littérature
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">Roman</span>
                                                <span class="badge badge-primary">Poésie</span>
                                                <span class="badge badge-primary">Théâtre</span>
                                            </td>
                                            <td>158</td>
                                            <td>
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <tr data-level="2">
                                            <td class="pl-4">
                                                <i class="fa fa-angle-right mr-2"></i>
                                                Roman Policier
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">Polar</span>
                                                <span class="badge badge-secondary">Thriller</span>
                                            </td>
                                            <td>47</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr data-level="1">
                                            <td>
                                                <i class="fa fa-folder-open text-info mr-2"></i>
                                                Sciences
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">Mathématiques</span>
                                                <span class="badge badge-primary">Physique</span>
                                            </td>
                                            <td>89</td>
                                            <td>
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr data-level="1">
                                            <td>
                                                <i class="fa fa-folder-open text-danger mr-2"></i>
                                                Histoire
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">Antique</span>
                                                <span class="badge badge-primary">Moderne</span>
                                            </td>
                                            <td>203</td>
                                            <td>
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
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

    <!-- SCRIPTS -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js')}}"></script>
    <script src="{{ url('assets/js/app.min.js')}}"></script>
    <style>
    tr[data-level="2"] td {
        background-color: #f8f9fa;
        font-style: italic;
    }
    .badge {
        margin-right: 5px;
    }
    </style>

    <script>
    $(document).ready(function() {
        $('#classification-table').on('click', 'button.btn-success', function() {
            // Logique pour ajouter une sous-catégorie
            console.log('Ajouter une sous-catégorie');
        });

        $('tr[data-level="1"]').click(function() {
            $(this).nextUntil('tr[data-level="1"]').toggle();
        });
    });
    </script>
</body>
</html>