<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css')}}" rel="stylesheet" />
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
                            <!-- Statistiques -->
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

                            <!-- Outils de gestion -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUserModal">
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

                            <!-- Tableau des utilisateurs -->
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
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#userDetailModal">
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
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#userDetailModal">
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

        <!-- Modals -->
        <!-- Modals -->
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
                                    <img src="https://via.placeholder.com/150" class="img-fluid rounded-circle mb-3" alt="Avatar">
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ... (même modal de formulaire que précédemment) ... -->
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
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="content.create">
                                        <label class="form-check-label">Création</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="content.edit">
                                        <label class="form-check-label">Modification</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="permission-category">
                                    <h6>Administration</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="users.manage">
                                        <label class="form-check-label">Gestion utilisateurs</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="settings.manage">
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

        <!-- Scripts -->
        <!-- Scripts -->
        <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
        <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js')}}"></script>
        <script src="{{ url('./assets/vendors/DataTables/datatables.min.js')}}"></script>
        <script src="{{ url('assets/js/app.min.js')}}"></script>

        <script>
            $(document).ready(function() {
                // Données locales
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

                // Initialisation DataTable
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

                // Gestion du formulaire
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

                // Suppression d'utilisateur
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

</html>