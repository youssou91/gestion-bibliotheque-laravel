{{-- <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card-header bg-dark text-white">
                        <h1 class="mt-4"><i class="fa fa-users-cog mr-2"></i>Gestion des Utilisateurs</h1>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-muted">Total Utilisateurs</h5>
                                            <h2 class="text-primary">245</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-muted">Utilisateurs Actifs</h5>
                                            <h2 class="text-success">198</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-muted">Administrateurs</h5>
                                            <h2 class="text-danger">12</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#addUserModal">
                                        <i class="fa fa-plus"></i> Nouvel Utilisateur
                                    </button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary">Export CSV</button>
                                        <button class="btn btn-sm btn-outline-secondary">Export PDF</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover" id="users-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Inscription</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#USR-001</td>
                                            <td>Jean Dupont</td>
                                            <td>jean.dupont@example.com</td>
                                            <td>
                                                <span class="badge badge-danger">Admin</span>
                                            </td>
                                            <td>15/09/2022</td>
                                            <td>
                                                <span class="badge badge-success">Actif</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#userDetailModal">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#USR-002</td>
                                            <td>Marie Leroy</td>
                                            <td>marie.leroy@example.com</td>
                                            <td>
                                                <span class="badge badge-warning">Éditeur</span>
                                            </td>
                                            <td>20/08/2023</td>
                                            <td>
                                                <span class="badge badge-secondary">Inactif</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#userDetailModal">
                                                    <i class="fa fa-eye"></i>
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

        <div class="modal fade" id="userDetailModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de l'utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <img src="https://via.placeholder.com/150" class="img-fluid rounded-circle mb-3"
                                        alt="Avatar">
                                    <h4>Jean Dupont</h4>
                                    <span class="badge badge-danger">Admin</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <dl class="row">
                                    <dt class="col-sm-4">Email:</dt>
                                    <dd class="col-sm-8">jean.dupont@example.com</dd>

                                    <dt class="col-sm-4">Inscrit le:</dt>
                                    <dd class="col-sm-8">15/09/2022</dd>

                                    <dt class="col-sm-4">Dernière connexion:</dt>
                                    <dd class="col-sm-8">Il y a 2 heures</dd>

                                    <dt class="col-sm-4">Permissions:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-primary">Gestion Utilisateurs</span>
                                        <span class="badge badge-primary">Modération</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="userFormModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Formulaire Utilisateur</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form id="userForm">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" id="user_id" name="id">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Nom complet</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Adresse email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Rôle</label>
                                        <select class="form-control" name="role" required>
                                            <option value="user">Utilisateur</option>
                                            <option value="editor">Éditeur</option>
                                            <option value="admin">Administrateur</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Statut</label>
                                        <select class="form-control" name="status" required>
                                            <option value="active">Actif</option>
                                            <option value="inactive">Inactif</option>
                                            <option value="suspended">Suspendu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="permissionsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Gestion des Permissions</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="permission-category">
                                    <h6>Gestion de contenu</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="content.create">
                                        <label class="form-check-label">Création</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="content.edit">
                                        <label class="form-check-label">Modification</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="permission-category">
                                    <h6>Administration</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="users.manage">
                                        <label class="form-check-label">Gestion utilisateurs</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="settings.manage">
                                        <label class="form-check-label">Paramètres</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="savePermissions">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
        <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
        <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
        <script src="{{ url('assets/js/app.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                const usersData = [{
                        id: 1,
                        name: "Jean Dupont",
                        email: "jean@exemple.com",
                        role: "admin",
                        created_at: "2023-01-15",
                        status: "active",
                        permissions: ["users.manage"]
                    },
                    {
                        id: 2,
                        name: "Marie Leroy",
                        email: "marie@exemple.com",
                        role: "editor",
                        created_at: "2023-02-20",
                        status: "inactive",
                        permissions: ["content.edit"]
                    }
                ];

                $('#users-table').DataTable({
                    data: usersData,
                    columns: [{
                            data: 'id',
                            title: 'ID'
                        },
                        {
                            data: 'name',
                            title: 'Nom'
                        },
                        {
                            data: 'email',
                            title: 'Email'
                        },
                        {
                            data: 'role',
                            title: 'Rôle',
                            render: function(data) {
                                const roles = {
                                    admin: 'danger',
                                    editor: 'warning',
                                    user: 'secondary'
                                };
                                return `<span class="badge badge-${roles[data]}">${data}</span>`;
                            }
                        },
                        {
                            data: 'created_at',
                            title: 'Inscription'
                        },
                        {
                            data: 'status',
                            title: 'Statut',
                            render: function(data) {
                                const statuses = {
                                    active: 'success',
                                    inactive: 'secondary',
                                    suspended: 'danger'
                                };
                                return `<span class="badge badge-${statuses[data]}">${data}</span>`;
                            }
                        },
                        {
                            title: 'Actions',
                            render: function(data, type, row) {
                                return `
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                            }
                        }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                    }
                });

                $('#userForm').submit(function(e) {
                    e.preventDefault();
                    const newUser = {
                        id: usersData.length + 1,
                        name: $('[name="name"]').val(),
                        email: $('[name="email"]').val(),
                        role: $('[name="role"]').val(),
                        status: $('[name="status"]').val(),
                        permissions: []
                    };

                    usersData.push(newUser);
                    $('#users-table').DataTable().row.add(newUser).draw();
                    $('#userFormModal').modal('hide');
                });

                $('#users-table').on('click', '.btn-delete', function() {
                    const userId = $(this).data('id');
                    const table = $('#users-table').DataTable();
                    const rowIndex = table.row($(this).parents('tr')).index();

                    usersData.splice(rowIndex, 1);
                    table.row($(this).parents('tr')).remove().draw();
                });
            });
        </script>


        <style>
            .badge-danger {
                background-color: #dc3545;
            }

            .badge-warning {
                background-color: #ffc107;
            }

            .badge-success {
                background-color: #28a745;
            }

            .badge-secondary {
                background-color: #6c757d;
            }

            .permission-category {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 15px;
            }
        </style>
</body>

</html> --}}
{{-- @section('content') --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mt-4"><i class="fas fa-users-cog mr-2"></i>Gestion des Utilisateurs</h1>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <!-- Carte Total -->
                            <div class="col-xl-2 col-md-4 mb-4">
                                <div class="card stat-card border-0 bg-light-primary">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-primary text-white mb-2">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['total'] }}</h3>
                                        <p class="text-muted mb-0">Total Utilisateurs</p>
                                        <div class="text-xs text-primary mt-1">
                                            <i class="fas fa-calendar-alt"></i> {{ now()->format('M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Carte Actifs -->
                            <div class="col-xl-2 col-md-4 mb-4">
                                <div class="card stat-card border-0 bg-light-success">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-success text-white mb-2">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['actifs'] }}</h3>
                                        <p class="text-muted mb-0">Utilisateurs Actifs</p>
                                        <div class="text-xs text-success mt-1">
                                            {{ $stats['total'] > 0 ? round(($stats['actifs'] / $stats['total']) * 100, 1) : 0 }}%
                                            du total
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Carte Admins -->
                            <div class="col-xl-2 col-md-4 mb-4">
                                <div class="card stat-card border-0 bg-light-danger">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-danger text-white mb-2">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['admin'] }}</h3>
                                        <p class="text-muted mb-0">Administrateurs</p>
                                        <div class="text-xs text-danger mt-1">
                                            <i class="fas fa-crown"></i> Haut privilège
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Carte Gestionnaires -->
                            <div class="col-xl-2 col-md-4 mb-4">
                                <div class="card stat-card border-0 bg-light-warning">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-warning text-white mb-2">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['gestionnaires'] }}</h3>
                                        <p class="text-muted mb-0">Gestionnaires</p>
                                        <div class="text-xs text-warning mt-1">
                                            <i class="fas fa-key"></i> Accès modéré
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Carte Éditeurs -->
                            <div class="col-xl-2 col-md-4 mb-4">
                                <div class="card stat-card border-0 bg-light-info">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-info text-white mb-2">
                                            <i class="fas fa-user-edit"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['editeurs'] }}</h3>
                                        <p class="text-muted mb-0">Éditeurs</p>
                                        <div class="text-xs text-info mt-1">
                                            <i class="fas fa-pen-fancy"></i> Création & edition
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Carte Clients -->
                            <div class="col-xl-2 col-md-4 mb-4">
                                <div class="card stat-card border-0 bg-light-secondary">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-secondary text-white mb-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['clients'] }}</h3>
                                        <p class="text-muted mb-0">Clients</p>
                                        <div class="text-xs text-secondary mt-1">
                                            {{ $stats['total'] > 0 ? round(($stats['clients'] / $stats['total']) * 100, 1) : 0 }}%
                                            du total
                                        </div>
                                        <div class="text-xs text-secondary mt-1">
                                            <i class="fas fa-shopping-cart"></i> Accès limité
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            .stat-card {
                                transition: transform 0.2s;
                                border-radius: 10px;
                            }

                            .stat-card:hover {
                                transform: translateY(-3px);
                            }

                            .stat-icon {
                                width: 40px;
                                height: 40px;
                                margin: 0 auto;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            }

                            .bg-light-primary {
                                background-color: rgba(13, 110, 253, 0.1) !important;
                            }

                            .bg-light-success {
                                background-color: rgba(25, 135, 84, 0.1) !important;
                            }

                            .bg-light-danger {
                                background-color: rgba(220, 53, 69, 0.1) !important;
                            }

                            .bg-light-warning {
                                background-color: rgba(255, 193, 7, 0.1) !important;
                            }

                            .bg-light-info {
                                background-color: rgba(13, 202, 240, 0.1) !important;
                            }

                            .bg-light-secondary {
                                background-color: rgba(108, 117, 125, 0.1) !important;
                            }
                        </style>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <a href="{{ url('admin.utilisateurs.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus mr-1"></i> Nouvel Utilisateur
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group">
                                    <a href="{{ url('admin.utilisateurs.export.csv') }}"
                                        class="btn btn-outline-secondary">
                                        <i class="fas fa-file-csv mr-1"></i> Export CSV
                                    </a>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des utilisateurs -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="users-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom Complet</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Inscription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($utilisateurs as $utilisateur)
                                        <tr>
                                            <td>#USR-{{ str_pad($utilisateur->id, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($utilisateur->photo)
                                                        <img src="{{ asset('storage/' . $utilisateur->photo) }}"
                                                            class="rounded-circle mr-2" width="30" height="30"
                                                            alt="Photo profil">
                                                    @else
                                                        <div class="avatar-placeholder rounded-circle mr-2 bg-secondary text-white d-flex align-items-center justify-content-center"
                                                            style="width:30px;height:30px;">
                                                            {{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <span>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $utilisateur->email }}</td>
                                            <td>
                                                @php
                                                    // $badgeClass = [
                                                    //     Utilisateurs::ROLE_ADMIN => 'danger',
                                                    //     Utilisateurs::ROLE_EDITEUR => 'warning',
                                                    //     Utilisateurs::ROLE_GESTIONNAIRE => 'info',
                                                    //     Utilisateurs::ROLE_CLIENT => 'secondary'
                                                    // ][$utilisateur->role];
                                                @endphp
                                                <span
                                                    class="badge badge-
                                        {{-- {{ $badgeClass }} --}}
                                         ">
                                                    {{ ucfirst($utilisateur->role) }}
                                                </span>
                                            </td>
                                            <td>{{ $utilisateur->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'actif' => 'success',
                                                        'inactif' => 'secondary',
                                                        'suspendu' => 'danger',
                                                    ][$utilisateur->statut];
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ ucfirst($utilisateur->statut) }}
                                                </span>
                                            </td>
                                            <td class="text-center" style="width: 150px">
                                                {{-- <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ url('admin.utilisateurs.edit', $utilisateur->id) }}"
                                                        class="btn btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ url('admin.utilisateurs.show', $utilisateur->id) }}"
                                                        class="btn btn-info" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form
                                                        action="{{ url('admin.utilisateurs.destroy', $utilisateur->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            title="Supprimer"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div> --}}
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Actions utilisateur">
                                                    <!-- Bouton Modifier -->
                                                    <a href="{{ url('admin.utilisateurs.edit', $utilisateur->id) }}"
                                                        class="btn btn-outline-warning mx-1" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Bouton Voir détails -->
                                                    <a href="{{ url('admin.utilisateurs.show', $utilisateur->id) }}"
                                                        class="btn btn-outline-info mx-1" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Bouton Blocage/Déblocage -->
                                                    <form
                                                        action="{{ url('admin.utilisateurs.toggle-status', $utilisateur->id) }}"
                                                        method="POST" class="d-inline mx-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="btn {{ $utilisateur->statut === 'actif' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                            title="{{ $utilisateur->statut === 'actif' ? 'Bloquer' : 'Débloquer' }}"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir {{ $utilisateur->statut === 'actif' ? 'bloquer' : 'débloquer' }} cet utilisateur?')">
                                                            <i
                                                                class="fas {{ $utilisateur->statut === 'actif' ? 'fa-lock' : 'fa-unlock' }}"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $utilisateurs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @include('includes.footer') --}}
</body>
{{-- @endsection --}}

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                responsive: true,
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                        orderable: false,
                        targets: [6]
                    } // Désactiver le tri sur la colonne Actions
                ]
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        .avatar-placeholder {
            font-size: 12px;
            font-weight: bold;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .btn-group-sm>.btn,
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .card-header {
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }
    </style>
@endsection
