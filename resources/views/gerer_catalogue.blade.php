<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Catalogue</title>
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
                    <div class="card-header bg-primary text-white">
                        <h1 class="mt-4"><i class="fa fa-bookmark mr-2"></i>Gestion du Catalogue</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="catalogue-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Catégorie</th>
                                            <th>ISBN</th>
                                            {{-- <th>Statut</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CAT-001</td>
                                            <td>Le Seigneur des Anneaux</td>
                                            <td>J.R.R. Tolkien</td>
                                            <td>Fantasy</td>
                                            <td>978-2-266-17234-6</td>
                                            <td><span class="badge badge-success">Disponible</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CAT-002</td>
                                            <td>Fondation</td>
                                            <td>Isaac Asimov</td>
                                            <td>Science-Fiction</td>
                                            <td>978-2-277-25001-2</td>
                                            <td><span class="badge badge-warning">Réservé</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CAT-003</td>
                                            <td>Les Misérables</td>
                                            <td>Victor Hugo</td>
                                            <td>Classique</td>
                                            <td>978-2-070-40123-4</td>
                                            <td><span class="badge badge-danger">Emprunté</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CAT-004</td>
                                            <td>1984</td>
                                            <td>George Orwell</td>
                                            <td>Dystopie</td>
                                            <td>978-2-070-03672-3</td>
                                            <td><span class="badge badge-success">Disponible</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
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

    <script type="text/javascript">
        $(function() {
            $('#catalogue-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { 
                        targets: 4,
                        render: function(data) {
                            return data.replace(/(\d{3})(\d{1})(\d{4})(\d{4})(\d{2})/, "$1-$2-$3-$4-$5");
                        }
                    },
                    { 
                        targets: 5,
                        render: function(data) {
                            return $(data).text();
                        }
                    },
                    { targets: 6, orderable: false, searchable: false }
                ]
            });
        })
    </script>
</body>
</html>