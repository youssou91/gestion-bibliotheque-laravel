@extends('frontOffice.layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i> Profil Client</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="avatar-wrapper">
                                <img src="{{ asset('assets/img/' . ($donneesProfil['photo'] ?? 'default-avatar.png')) }}"
                                    class="img-thumbnail rounded-circle mb-3" alt="Avatar"
                                    style="width: 150px; height: 150px;">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" readonly class="form-control" value="{{ $donneesProfil['nom_complet'] }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" readonly class="form-control" value="{{ $donneesProfil['email'] }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" readonly class="form-control" value="{{ $donneesProfil['telephone'] ?? 'Non renseigné' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" readonly class="form-control" value="{{ $donneesProfil['adresse'] ?? 'Non renseignée' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date d'inscription</label>
                                    <input type="text" readonly class="form-control" value="{{ $donneesProfil['date_inscription'] }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dernière connexion</label>
                                    <input type="text" readonly class="form-control" value="{{ $donneesProfil['date_derniere_connexion'] ?? 'Jamais' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rôle</label>
                                    <span class="badge bg-info text-capitalize">{{ $donneesProfil['role'] }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <span class="badge bg-{{ $donneesProfil['statut'] === 'actif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($donneesProfil['statut']) }}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ url('profile.edit') }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i> Modifier le profil
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
