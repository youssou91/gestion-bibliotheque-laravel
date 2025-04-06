<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
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
                    <div class="card-header bg-warning text-white">
                        <h1 class="mt-4"><i class="fa fa-cubes mr-2"></i>Gestion du Stock</h1>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Stock total : 1 245 ouvrages
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover" id="stock-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Titre</th>
                                            <th>Disponible</th>
                                            <th>Empruntés</th>
                                            <th>Localisation</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>STK-001</td>
                                            <td>Le Petit Prince</td>
                                            <td>12</td>
                                            <td>3</td>
                                            <td>Rayon A2</td>
                                            <td>
                                                <span class="badge badge-success">En stock</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>STK-002</td>
                                            <td>1984</td>
                                            <td>2</td>
                                            <td>8</td>
                                            <td>Rayon B7</td>
                                            <td>
                                                <span class="badge badge-warning">Stock faible</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>STK-003</td>
                                            <td>L'Étranger</td>
                                            <td>0</td>
                                            <td>10</td>
                                            <td>Rayon C3</td>
                                            <td>
                                                <span class="badge badge-danger">Rupture</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>STK-004</td>
                                            <td>Madame Bovary</td>
                                            <td>5</td>
                                            <td>2</td>
                                            <td>Rayon A5</td>
                                            <td>
                                                <span class="badge badge-success">En stock</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-exchange"></i>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#stock-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { targets: 2, type: 'num' },
                    { targets: 3, type: 'num' },
                    { 
                        targets: 5,
                        render: function(data) {
                            return $(data).text();
                        }
                    },
                    { targets: 6, orderable: false, searchable: false }
                ],
                order: [[2, 'asc']]
            });
        });
    </script>
</body>
</html>