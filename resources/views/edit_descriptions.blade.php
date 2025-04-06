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
                    <div class="card-header bg-info text-white">
                        <h1 class="mt-4"><i class="fa fa-book mr-2"></i>Gestion des Ouvrages</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="livres-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Titre</th>
                                            <th>Genre</th>
                                            <th>Année</th>
                                            <th>Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>001</td>
                                            <td>Le Petit Prince</td>
                                            <td>Conte philosophique</td>
                                            <td>1943</td>
                                            <td>15</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>002</td>
                                            <td>1984</td>
                                            <td>Roman dystopique</td>
                                            <td>1949</td>
                                            <td>8</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>003</td>
                                            <td>L'Étranger</td>
                                            <td>Roman philosophique</td>
                                            <td>1942</td>
                                            <td>12</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>004</td>
                                            <td>Madame Bovary</td>
                                            <td>Roman réaliste</td>
                                            <td>1856</td>
                                            <td>5</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
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
            $('#livres-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { targets: 0, width: '5%' },
                    { targets: 3, type: 'num' },
                    { targets: 4, orderable: false },
                    { targets: 5, orderable: false, searchable: false }
                ]
            });
        })
    </script>
</body>
</html>