<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                            {{-- <div class="col-md-6">
                                <a href="" class="btn btn-primary">
                                    <i class="fas fa-user-plus mr-1"></i> Nouvel Utilisateur
                                </a>
                            </div> --}}
                            <div class="col-md-6">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#ajouterUtilisateurModal">
                                    <i class="fas fa-user-plus mr-1"></i> Nouvel Utilisateur
                                </button>
                            </div>
                            {{-- MODAL --}}
                            <!-- Modal Ajouter Utilisateur -->
                            <div class="modal fade" id="ajouterUtilisateurModal" tabindex="-1" role="dialog"
                                aria-labelledby="ajouterUtilisateurModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="{{ route('admin.utilisateurs.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="ajouterUtilisateurModalLabel">Ajouter un
                                                    utilisateur</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Fermer">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Champs du formulaire -->
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Nom</label>
                                                        <input type="text" name="nom" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Prénom</label>
                                                        <input type="text" name="prenom" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        required>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Rôle</label>
                                                        <select name="role" class="form-control" required>
                                                            <option value="" disabled selected>-- Sélectionner un rôle --</option>
                                                            <option value="client">Client</option>
                                                            <option value="editeur">Éditeur</option>
                                                            <option value="gestionnaire">Gestionnaire</option>
                                                            <option value="admin">Administrateur</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Téléphone</label>
                                                        <input type="text" name="telephone" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Adresse</label>
                                                        <textarea name="adresse" class="form-control" rows="6"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Photo</label>
                                                        <input type="file" name="photo" class="form-control-file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Créer
                                                    l'utilisateur</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                                                <span
                                                    class="badge badge-{{ $utilisateur->role === 'admin' ? 'danger' : ($utilisateur->role === 'editeur' ? 'warning' : ($utilisateur->role === 'gestionnaire' ? 'info' : 'secondary')) }}">
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
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Actions utilisateur">
                                                    <!-- Bouton Modifier -->
                                                    <button type="button" class="btn btn-outline-warning mx-1"
                                                        title="Modifier" data-toggle="modal"
                                                        data-target="#editUserModal-{{ $utilisateur->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- Bouton Voir détails -->
                                                    <button type="button" class="btn btn-outline-info mx-1"
                                                        title="Voir détails" data-toggle="modal"
                                                        data-target="#viewUserModal-{{ $utilisateur->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Bouton Blocage/Déblocage -->
                                                    <button type="button"
                                                        class="btn {{ $utilisateur->statut === 'actif' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        title="{{ $utilisateur->statut === 'actif' ? 'Bloquer' : 'Débloquer' }}"
                                                        data-toggle="modal"
                                                        data-target="#confirmToggleModal-{{ $utilisateur->id }}">
                                                        <i
                                                            class="fas {{ $utilisateur->statut === 'actif' ? 'fa-lock' : 'fa-unlock' }}"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal pour voir les détails -->
                                        <div class="modal fade" id="viewUserModal-{{ $utilisateur->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="viewUserModalLabel-{{ $utilisateur->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="viewUserModalLabel-{{ $utilisateur->id }}">Détails de
                                                            {{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Fermer">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Email:</strong> {{ $utilisateur->email }}</p>
                                                        <p><strong>Rôle:</strong> {{ ucfirst($utilisateur->role) }}</p>
                                                        <p><strong>Inscription:</strong>
                                                            {{ $utilisateur->created_at->format('d/m/Y') }}</p>
                                                        <p><strong>Statut:</strong> {{ ucfirst($utilisateur->statut) }}
                                                        </p>
                                                        <!-- Ajoute d'autres infos si besoin -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal pour modifier -->
                                        <div class="modal fade" id="editUserModal-{{ $utilisateur->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="editUserModalLabel-{{ $utilisateur->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('admin.utilisateurs.update', $utilisateur->id) }}"
                                                        method="POST" class="modal-content">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editUserModalLabel-{{ $utilisateur->id }}">
                                                                Modifier
                                                                {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
                                                            </h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Fermer">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Exemple de champs modifiables -->
                                                            <div class="form-group">
                                                                <label
                                                                    for="prenom-{{ $utilisateur->id }}">Prénom</label>
                                                                <input type="text" class="form-control"
                                                                    id="prenom-{{ $utilisateur->id }}" name="prenom"
                                                                    value="{{ $utilisateur->prenom }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="nom-{{ $utilisateur->id }}">Nom</label>
                                                                <input type="text" class="form-control"
                                                                    id="nom-{{ $utilisateur->id }}" name="nom"
                                                                    value="{{ $utilisateur->nom }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label
                                                                    for="email-{{ $utilisateur->id }}">Email</label>
                                                                <input type="email" class="form-control"
                                                                    id="email-{{ $utilisateur->id }}" name="email"
                                                                    value="{{ $utilisateur->email }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="role-{{ $utilisateur->id }}">Rôle</label>
                                                                <select class="form-control"
                                                                    id="role-{{ $utilisateur->id }}" name="role">
                                                                    <option value="admin"
                                                                        {{ $utilisateur->role == 'admin' ? 'selected' : '' }}>
                                                                        Admin</option>
                                                                    <option value="editeur"
                                                                        {{ $utilisateur->role == 'editeur' ? 'selected' : '' }}>
                                                                        Éditeur</option>
                                                                    <option value="gestionnaire"
                                                                        {{ $utilisateur->role == 'gestionnaire' ? 'selected' : '' }}>
                                                                        Gestionnaire</option>
                                                                    <option value="utilisateur"
                                                                        {{ $utilisateur->role == 'utilisateur' ? 'selected' : '' }}>
                                                                        Utilisateur</option>
                                                                </select>
                                                            </div>
                                                            <!-- Ajoute d'autres champs selon besoin -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Annuler</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de confirmation pour le blocage/déblocage -->
                                        <div class="modal fade" id="confirmToggleModal-{{ $utilisateur->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="confirmToggleModalLabel-{{ $utilisateur->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('admin.utilisateurs.toggle-status', $utilisateur->id) }}"
                                                        method="POST" class="modal-content">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="confirmToggleModalLabel-{{ $utilisateur->id }}">
                                                                Confirmer l'action</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Fermer">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Êtes-vous sûr de vouloir
                                                            {{ $utilisateur->statut === 'actif' ? 'bloquer' : 'débloquer' }}
                                                            cet utilisateur ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Annuler</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Confirmer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour voir les détails -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">Détails de l'utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="viewUserModalBody">
                    <!-- Le contenu sera chargé via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('includes.footer') --}}
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
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
    <script>
        $(document).ready(function() {
            // Gestion du modal "Voir détails"
            $('.view-user-btn').click(function() {
                var userId = $(this).data('user-id');

                $.ajax({
                    url: '/admin/utilisateurs/' + userId,
                    method: 'GET',
                    success: function(response) {
                        $('#viewUserModalBody').html(response);
                        $('#viewUserModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue lors du chargement des détails.');
                    }
                });
            });

            // Gestion du modal "Modifier"
            $('.edit-user-btn').click(function() {
                var userId = $(this).data('user-id');

                $.ajax({
                    url: '/admin/utilisateurs/' + userId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#editUserModalBody').html(response);
                        $('#editUserModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue lors du chargement du formulaire.');
                    }
                });
            });

            // Gestion de l'enregistrement des modifications
            $('#saveUserChanges').click(function() {
                var form = $('#editUserModalBody form');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        $('#editUserModal').modal('hide');
                        location.reload(); // Recharger la page pour voir les modifications
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue lors de la mise à jour.');
                    }
                });
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
