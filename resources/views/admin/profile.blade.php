<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Profil Utilisateur</title>
    <!-- STYLES GLOBAUX -->
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    @stack('styles')
</head>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <div class="row mb-3">
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('admin/utilisateurs') }}">Gestion des utilisateurs</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profil utilisateur</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fa fa-user-circle mr-2"></i>
                                <h4 class="mb-0 d-inline">Profil Utilisateur</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="avatar-wrapper mb-3">
                                        <img src="{{ asset('assets/img/1745088414montre7.jpg') }}"
                                            class="img-thumbnail rounded-circle" alt="Avatar"
                                            style="width: 150px; height: 150px;">
                                    </div>
                                    <div class="user-actions mt-3">
                                        <button class="btn btn-outline-primary btn-block mb-2" data-toggle="modal" data-target="#resetPasswordModal">
                                            <i class="fa fa-key"></i> Réinitialiser mot de passe
                                        </button>
                                        <button class="btn btn-outline-info btn-block mb-2" data-toggle="modal" data-target="#changeRoleModal">
                                            <i class="fa fa-user-tag"></i> Modifier rôle
                                        </button>
                                        <a href="
                                            {{-- {{ url('admin/utilisateurs/' . $donneesProfil['id'] . '/historique') }} --}}
                                             " class="btn btn-outline-secondary btn-block">
                                            <i class="fa fa-history"></i> Historique
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="border-bottom pb-2 mb-3">Informations personnelles</h5>
                                            <form>
                                                <!-- Nom complet -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Nom complet</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly
                                                            class="form-control-plaintext font-weight-bold"
                                                            value="{{ $donneesProfil['nom_complet'] }}">
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Email</label>
                                                    <div class="col-sm-8">
                                                        <input type="email" readonly class="form-control-plaintext"
                                                            value="{{ $donneesProfil['email'] }}">
                                                    </div>
                                                </div>

                                                <!-- Téléphone -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Téléphone</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control-plaintext"
                                                            value="{{ $donneesProfil['telephone'] ?? 'Non renseigné' }}">
                                                    </div>
                                                </div>
                                                
                                                <!-- Adresse -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Adresse</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control-plaintext"
                                                            value="{{ $donneesProfil['adresse'] ?? 'Non renseignée' }}">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="border-bottom pb-2 mb-3">Informations système</h5>
                                            <form>
                                                <!-- Dates importantes -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Date d'inscription</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control-plaintext"
                                                            value="{{ $donneesProfil['date_inscription'] }}">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Dernière connexion</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" readonly class="form-control-plaintext"
                                                            value="{{ $donneesProfil['date_derniere_connexion'] ?? 'Jamais' }}">
                                                    </div>
                                                </div>

                                                <!-- Rôle et Statut -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Rôle</label>
                                                    <div class="col-sm-8">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge badge-info mr-2 text-capitalize">
                                                                {{ $donneesProfil['role'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Statut -->
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Statut</label>
                                                    <div class="col-sm-8">
                                                        <span
                                                            class="badge badge-{{ $donneesProfil['statut'] === 'actif' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($donneesProfil['statut']) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                
                                            </form>
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
    </div>

    <!-- SCRIPTS JS -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js') }}" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="{{ url('assets/js/app.min.js') }}" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="{{ url('./assets/js/scripts/dashboard_1_demo.js') }}" type="text/javascript"></script>
</body>

</html>