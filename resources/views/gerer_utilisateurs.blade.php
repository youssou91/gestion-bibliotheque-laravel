<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stat-card {
            transition: transform 0.2s;
            border-radius: 10px;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
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
            background-color: rgba(23, 162, 184, 0.1) !important;
        }

        .bg-light-secondary {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #6c757d;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')

    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <!-- En-tête -->
                <div class="card-header bg-primary text-white mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mt-2"><i class="fas fa-users-cog mr-2"></i>Gestion des Utilisateurs</h1>
                    </div>
                </div>

                <!-- Cartes de statistiques -->
                <div class="row mb-4">
                    <!-- Carte Total Utilisateurs -->
                    <div class="col-xl-2 col-md-4 mb-4">
                        <div class="card stat-card border-0 bg-light-primary">
                            <div class="card-body text-center p-3">
                                <div class="stat-icon bg-primary text-white mb-2">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="mb-1">{{ $stats['total'] }}</h3>
                                <p class="text-muted mb-0">Total Utilisateurs</p>
                                <div class="text-xs text-primary mt-1">
                                    <i class="fas fa-calendar-day"></i> {{ now()->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte Utilisateurs Actifs -->
                    <div class="col-xl-2 col-md-4 mb-4">
                        <div class="card stat-card border-0 bg-light-success">
                            <div class="card-body text-center p-3">
                                <div class="stat-icon bg-success text-white mb-2">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <h3 class="mb-1">{{ $stats['actifs'] }}</h3>
                                <p class="text-muted mb-0">Actifs</p>
                                <div class="text-xs text-success mt-1">
                                    {{ $stats['total'] > 0 ? round(($stats['actifs'] / $stats['total']) * 100, 1) : 0 }}%
                                    du total
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte Administrateurs -->
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
                                    <i class="fas fa-pen-fancy"></i> Créer & éditer
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des utilisateurs -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Liste des utilisateurs</h5>
                        <div class="d-flex">
                            <!-- Bouton Ajouter -->
                            <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#ajouterUtilisateurModal">
                                <i class="fas fa-user-plus mr-1"></i> Nouvel utilisateur
                            </button>
                            
                            <!-- Filtre par statut -->
                            <div class="btn-group btn-group-toggle mr-2" data-toggle="buttons">
                                <label class="btn btn-outline-primary btn-sm active" data-filter="all">
                                    <input type="radio" name="status-filter" checked> Tous
                                </label>
                                <label class="btn btn-outline-success btn-sm" data-filter="actif">
                                    <input type="radio" name="status-filter"> Actifs
                                </label>
                                <label class="btn btn-outline-secondary btn-sm" data-filter="inactif">
                                    <input type="radio" name="status-filter"> Inactifs
                                </label>
                            </div>
                            
                            <!-- Filtre par rôle -->
                            <select class="form-control form-control-sm" id="role-filter" style="width: 150px;">
                                <option value="all">Tous les rôles</option>
                                <option value="admin">Administrateurs</option>
                                <option value="gestionnaire">Gestionnaires</option>
                                <option value="editeur">Éditeurs</option>
                                <option value="client">Clients</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover" id="usersTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Inscription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($utilisateurs as $utilisateur)
                                        <tr data-status="{{ $utilisateur->statut }}" data-role="{{ $utilisateur->role }}">
                                            <td>#{{ str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($utilisateur->photo)
                                                        <img src="{{ asset('assets/img/' . $utilisateur->photo) }}" 
                                                             class="user-avatar mr-2" alt="Photo profil">
                                                    @else
                                                        <div class="avatar-placeholder mr-2">
                                                            {{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <span>
                                                        {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
                                                        @if($utilisateur->telephone)
                                                            <br><small class="text-muted">{{ $utilisateur->telephone }}</small>
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td>{{ $utilisateur->email }}</td>
                                            <td>
                                                @php
                                                    $roleClass = [
                                                        'admin' => 'danger',
                                                        'gestionnaire' => 'info',
                                                        'editeur' => 'warning',
                                                        'client' => 'secondary'
                                                    ][$utilisateur->role];
                                                @endphp
                                                <span class="badge badge-{{ $roleClass }}">
                                                    {{ ucfirst($utilisateur->role) }}
                                                </span>
                                            </td>
                                            <td>{{ $utilisateur->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'actif' => 'success',
                                                        'inactif' => 'secondary',
                                                        'suspendu' => 'danger'
                                                    ][$utilisateur->statut];
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ ucfirst($utilisateur->statut) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Bouton Voir détails -->
                                                    <button type="button" class="btn btn-outline-info mx-1"
                                                        title="Voir détails" data-toggle="modal"
                                                        data-target="#viewUserModal-{{ $utilisateur->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Bouton Modifier -->
                                                    {{-- <button type="button" class="btn btn-outline-warning mx-1"
                                                        title="Modifier" data-toggle="modal"
                                                        data-target="#editUserModal-{{ $utilisateur->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button> --}}

                                                    <!-- Bouton Changer statut -->
                                                    <button type="button" 
                                                        class="btn {{ $utilisateur->statut === 'actif' ? 'btn-outline-danger' : 'btn-outline-success' }} mx-1"
                                                        title="{{ $utilisateur->statut === 'actif' ? 'Désactiver' : 'Activer' }}"
                                                        data-toggle="modal"
                                                        data-target="#toggleStatusModal-{{ $utilisateur->id }}">
                                                        <i class="fas {{ $utilisateur->statut === 'actif' ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Affichage de <b>{{ $utilisateurs->firstItem() }}</b> à
                            <b>{{ $utilisateurs->lastItem() }}</b> sur <b>{{ $utilisateurs->total() }}</b> utilisateurs
                        </div>
                        <div>
                            {{ $utilisateurs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals pour chaque utilisateur -->
    @foreach ($utilisateurs as $utilisateur)
        <!-- Modal Détails Utilisateur -->
        <div class="modal fade" id="viewUserModal-{{ $utilisateur->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user mr-2"></i>
                            Utilisateur #{{ str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT) }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Section Profil -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-id-card mr-2"></i>Profil</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($utilisateur->photo)
                                                <img src="{{ asset('assets/img/' . $utilisateur->photo) }}" 
                                                     class="rounded-circle mr-3" width="80" height="80" alt="Photo profil">
                                            @else
                                                <div class="avatar-placeholder rounded-circle mr-3" 
                                                     style="width:80px;height:80px;font-size:1.5rem;">
                                                    {{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h4>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h4>
                                                <p class="text-muted mb-1">{{ $utilisateur->email }}</p>
                                                <span class="badge badge-{{ $roleClass }}">
                                                    {{ ucfirst($utilisateur->role) }}
                                                </span>
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-phone mr-2"></i>Téléphone</span>
                                                <span>{{ $utilisateur->telephone ?? 'Non renseigné' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-map-marker-alt mr-2"></i>Adresse</span>
                                                <span>{{ $utilisateur->adresse ?? 'Non renseignée' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Statut -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informations</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-day mr-2"></i>Date d'inscription</span>
                                                <span>{{ $utilisateur->created_at->format('d/m/Y ') }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-clock mr-2"></i>Statut</span>
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ ucfirst($utilisateur->statut) }}
                                                </span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-edit mr-2"></i>Dernière mise à jour</span>
                                                <span>{{ $utilisateur->updated_at->format('d/m/Y ') }}</span>
                                            </li>
                                            {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-sign-in-alt mr-2"></i>Dernière connexion</span>
                                                <span>{{ $utilisateur->last_login_at ? $utilisateur->last_login_at->format('d/m/Y ') : 'Jamais' }}</span>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Activité -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Activité récente</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4>{{ $utilisateur->emprunts_count ?? 0 }}</h4>
                                                    <p class="text-muted mb-0">Emprunts</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4>{{ $utilisateur->reservations_count ?? 0 }}</h4>
                                                    <p class="text-muted mb-0">Réservations</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4>{{ $utilisateur->amendes_count ?? 0 }}</h4>
                                                    <p class="text-muted mb-0">Amendes</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Modifier Utilisateur -->
        <div class="modal fade" id="editUserModal-{{ $utilisateur->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier l'utilisateur
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prénom</label>
                                        <input type="text" name="prenom" class="form-control" value="{{ $utilisateur->prenom }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom" class="form-control" value="{{ $utilisateur->nom }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $utilisateur->email }}" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rôle</label>
                                        <select name="role" class="form-control" required>
                                            <option value="admin" {{ $utilisateur->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                            <option value="gestionnaire" {{ $utilisateur->role == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                                            <option value="editeur" {{ $utilisateur->role == 'editeur' ? 'selected' : '' }}>Éditeur</option>
                                            <option value="client" {{ $utilisateur->role == 'client' ? 'selected' : '' }}>Client</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Statut</label>
                                        <select name="statut" class="form-control" required>
                                            <option value="actif" {{ $utilisateur->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                                            <option value="inactif" {{ $utilisateur->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                            <option value="suspendu" {{ $utilisateur->statut == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="telephone" class="form-control" value="{{ $utilisateur->telephone }}">
                            </div>
                            
                            <div class="form-group">
                                <label>Adresse</label>
                                <textarea name="adresse" class="form-control" rows="3">{{ $utilisateur->adresse }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Photo de profil</label>
                                <input type="file" name="photo" class="form-control-file">
                                @if($utilisateur->photo)
                                    <small class="text-muted">Laisser vide pour conserver l'actuelle</small>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Changer Statut -->
        <div class="modal fade" id="toggleStatusModal-{{ $utilisateur->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.utilisateurs.toggle-status', $utilisateur->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header bg-{{ $utilisateur->statut === 'actif' ? 'danger' : 'success' }} text-white">
                            <h5 class="modal-title">
                                <i class="fas {{ $utilisateur->statut === 'actif' ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                                {{ $utilisateur->statut === 'actif' ? 'Désactiver' : 'Activer' }} l'utilisateur
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir {{ $utilisateur->statut === 'actif' ? 'désactiver' : 'activer' }} 
                               l'utilisateur <strong>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</strong> ?</p>
                            @if($utilisateur->statut === 'actif')
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    L'utilisateur ne pourra plus se connecter jusqu'à réactivation.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-{{ $utilisateur->statut === 'actif' ? 'danger' : 'success' }}">
                                <i class="fas {{ $utilisateur->statut === 'actif' ? 'fa-ban' : 'fa-check' }} mr-1"></i>
                                {{ $utilisateur->statut === 'actif' ? 'Désactiver' : 'Activer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Ajouter Utilisateur -->
    <div class="modal fade" id="ajouterUtilisateurModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.utilisateurs.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus mr-2"></i>
                            Ajouter un nouvel utilisateur
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" name="prenom" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rôle</label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin">Administrateur</option>
                                        <option value="gestionnaire">Gestionnaire</option>
                                        <option value="editeur">Éditeur</option>
                                        <option value="client" selected>Client</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Statut</label>
                                    <select name="statut" class="form-control" required>
                                        <option value="actif" selected>Actif</option>
                                        <option value="inactif">Inactif</option>
                                        <option value="suspendu">Suspendu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label>Adresse</label>
                            <textarea name="adresse" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Photo de profil</label>
                            <input type="file" name="photo" class="form-control-file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialisation DataTables avec pagination côté serveur
            $('#usersTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { orderable: false, targets: [6] } // Désactiver le tri sur la colonne Actions
                ]
            });

            // Filtre par statut
            $('[data-filter]').on('click', function() {
                const filter = $(this).data('filter');
                
                if (filter === 'all') {
                    $('tbody tr').show();
                } else {
                    $('tbody tr').hide();
                    $('tbody tr[data-status="' + filter + '"]').show();
                }
            });

            // Filtre par rôle
            $('#role-filter').on('change', function() {
                const role = $(this).val();
                
                if (role === 'all') {
                    $('tbody tr').show();
                } else {
                    $('tbody tr').hide();
                    $('tbody tr[data-role="' + role + '"]').show();
                }
            });
        });
    </script>
</body>

</html>