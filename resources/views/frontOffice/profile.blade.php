@extends('frontOffice.layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-user-circle me-2"></i> Mon Profil
                            </h4>
                            <span class="badge bg-white text-primary fs-6">
                                <i class="fas fa-user-tag me-1"></i> {{ ucfirst($donneesProfil['role']) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Colonne Photo + Stats -->
                            <div class="col-md-4 text-center border-end">
                                <div class="position-relative mb-4">
                                    <img src="{{ asset('assets/img/' . (isset($donneesProfil['photo']) && !empty($donneesProfil['photo']) && file_exists(public_path('assets/img/' . $donneesProfil['photo'])) ? $donneesProfil['photo'] : '1745088414montre7.jpg')) }}"
                                        onerror="this.src='{{ asset('assets/img/1745088414montre7.jpg') }}'"
                                        class="img-thumbnail rounded-circle" alt="Photo de profil"
                                        style="width: 180px; height: 180px; object-fit: cover;">
                                    <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow">
                                        <a href="#" class="text-primary" data-bs-toggle="modal"
                                            data-bs-target="#avatarModal">
                                            <i class="fas fa-camera"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="stats-box p-3 mb-4 rounded bg-light">
                                    <h5 class="text-center mb-3">Mes Statistiques</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-book me-2 text-primary"></i> Emprunts (en cours)</span>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $donneesProfil['emprunts_count'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-heart me-2 text-danger"></i> Favoris</span>
                                        <span class="badge bg-danger rounded-pill">
                                            {{ $donneesProfil['favoris_count'] ?? 0 }}
                                        </span>
                                    </div>
                                    {{-- <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-comment me-2 text-info"></i> Commentaires</span>
                                        <span class="badge bg-info rounded-pill">
                                            {{ $donneesProfil['commentaires_count'] ?? 0 }}
                                        </span>
                                    </div> --}}
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('frontOffice.emprunts') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-book-open me-1"></i> Mes Emprunts
                                    </a>
                                    <a href="{{ route('frontOffice.favoris') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-heart me-1"></i> Mes Favoris
                                    </a>
                                </div>
                            </div>

                            <!-- Colonne Informations -->
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Informations Personnelles</h5>
                                    <a href="{{ url('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light" id="nom"
                                                value="{{ $donneesProfil['nom_complet'] }}" readonly>
                                            <label for="nom">Nom complet</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control bg-light" id="email"
                                                value="{{ $donneesProfil['email'] }}" readonly>
                                            <label for="email">Adresse email</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light" id="telephone"
                                                value="{{ $donneesProfil['telephone'] ?? 'Non renseigné' }}" readonly>
                                            <label for="telephone">Téléphone</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light" id="adresse"
                                                value="{{ $donneesProfil['adresse'] ?? 'Non renseignée' }}" readonly>
                                            <label for="adresse">Adresse</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light" id="inscription"
                                                value="{{ $donneesProfil['date_inscription'] }}" readonly>
                                            <label for="inscription">Membre depuis</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light" id="connexion"
                                                value="{{ $donneesProfil['date_derniere_connexion'] ?? 'Jamais' }}"
                                                readonly>
                                            <label for="connexion">Dernière connexion</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex align-items-center mt-2">
                                            <span class="me-2">Statut :</span>
                                            <span
                                                class="badge rounded-pill py-2 px-3 bg-{{ $donneesProfil['statut'] === 'actif' ? 'success' : 'secondary' }}">
                                                <i
                                                    class="fas fa-{{ $donneesProfil['statut'] === 'actif' ? 'check-circle' : 'clock' }} me-1"></i>
                                                {{ ucfirst($donneesProfil['statut']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <!-- Section Sécurité -->
                                <h5 class="mb-3">Sécurité du compte</h5>
                                <div class="list-group mb-4">
                                    <a href="{{ url('password.change') }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-lock me-2 text-warning"></i> Changer le mot de passe</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a>
                                    <a href="#"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-envelope me-2 text-info"></i> Modifier l'email</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a>
                                    <a href="#"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-bell me-2 text-primary"></i> Préférences de
                                            notification</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour changer la photo de profil -->
    <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changer la photo de profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="avatarForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="avatarUpload" class="form-label">Sélectionner une image</label>
                            <input class="form-control" type="file" id="avatarUpload" accept="image/*">
                        </div>
                        <div class="text-center">
                            <div class="avatar-preview mb-3">
                                <img src="{{ asset('assets/img/' . ($donneesProfil['photo'] ?? 'default-avatar.jpg')) }}"
                                    class="img-thumbnail rounded-circle"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-box {
            border-left: 4px solid var(--bs-primary);
        }

        .form-floating .form-control:read-only {
            background-color: #f8f9fa;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
