@extends('frontOffice.layouts.app')
@section('content')
    <div class="container py-5">
        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

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
                                        <span><i class="fas fa-bookmark me-2 text-success"></i> Réservations
                                            (attente)</span>
                                        <span class="badge bg-success rounded-pill">
                                            {{ $donneesProfil['reservations_count'] ?? 0 }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-exclamation-triangle me-2 text-warning"></i> Amendes
                                            (impayées)</span>
                                        <span class="badge bg-warning rounded-pill">
                                            {{ $donneesProfil['amendes_count'] ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('frontOffice.emprunts') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-book-open me-1"></i> Mes Emprunts
                                    </a>
                                    <a href="{{ route('frontOffice.favoris') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-heart me-1"></i> Mes Favoris
                                    </a>
                                    <button class="btn btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#amendesModal">
                                        <i class="fas fa-exclamation-circle me-1"></i> Mes Amendes
                                    </button>
                                </div>
                            </div>

                            <!-- Colonne Informations -->
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Informations Personnelles</h5>
                                    <a href="{{ url('profile.edit') }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-edit me-1"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-success rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#carteMembreModal">
                                        <i class="fas fa-id-card me-1"></i>
                                    </button>
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
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#passwordModal"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-lock me-2 text-warning"></i> Changer le mot de passe</span>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour changer le mot de passe -->
        <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Changer le mot de passe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <form id="passwordForm" method="POST" action="{{ route('frontOffice.updatePassword') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $donneesProfil['id'] }}">
                            <!-- Champ caché pour l'email pour l'accessibilité -->
                            <input type="hidden" name="username" autocomplete="username" value="{{ $donneesProfil['email'] }}">

                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_password"
                                    required autocomplete="current-password" aria-describedby="currentPasswordHelp">
                                <div id="currentPasswordHelp" class="form-text">Saisissez votre mot de passe actuel.</div>
                            </div>

                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="newPassword" name="new_password"
                                    required autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="confirmPassword"
                                    name="new_password_confirmation" required autocomplete="new-password">
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

        <!-- Modal pour changer la photo de profil -->
        <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Changer la photo de profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="avatarForm" method="POST" enctype="multipart/form-data"
                            action="{{ route('frontOffice.updateAvatar') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $donneesProfil['id'] }}">
                            <div class="mb-3">
                                <label for="avatarUpload" class="form-label">Sélectionner une image</label>
                                <input class="form-control" type="file" id="avatarUpload" name="photo"
                                    accept="image/*">
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

        <!-- Modal Carte de Membre -->
        <div class="modal fade" id="carteMembreModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Ma Carte de Membre</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-member text-center p-4">
                            <div class="card-header bg-light py-3 mb-3 rounded">
                                <h4 class="text-primary mb-0">Bibliothèque Municipale</h4>
                                <small class="text-muted">Carte de membre</small>
                            </div>

                            <div class="member-photo mb-3">
                                <img src="{{ asset('assets/img/' . ($donneesProfil['photo'] ?? 'default-avatar.jpg')) }}"
                                    class="img-thumbnail rounded-circle"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            </div>

                            <div class="member-info mb-4">
                                <h5 class="mb-1">{{ $donneesProfil['nom_complet'] }}</h5>
                                <p class="text-muted mb-1">Membre #-CLT-{{ $donneesProfil['id'] . '-123#' ?? '0000' }}</p>
                                <p class="mb-0">
                                    <span
                                        class="badge bg-{{ $donneesProfil['statut'] === 'actif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($donneesProfil['statut']) }}
                                    </span>
                                </p>
                            </div>

                            <div class="card-footer bg-light p-3 rounded">
                                <small class="text-muted">Membre depuis {{ $donneesProfil['date_inscription'] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal des Amendes -->
        <div class="modal fade" id="amendesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-warning text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-circle me-2"></i> Mes Amendes
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($amendes->isEmpty())
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Vous n'avez aucune amende impayée.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Livre</th>
                                            <th>Motif</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($amendes as $amende)
                                            <tr>
                                                <td>{{ $amende->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $amende->emprunt->ouvrage->titre }}</td>
                                                <td>{{ $amende->motif }}</td>
                                                <td class="fw-bold">$ {{ number_format($amende->montant, 2) }} </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $amende->est_payee ? 'success' : 'danger' }}">
                                                        {{ $amende->est_payee ? 'Payée' : 'Impayée' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if (!$amende->est_payee)
                                                        <button class="btn btn-sm btn-outline-primary btn-payer"
                                                            data-id="{{ $amende->id }}"
                                                            data-montant="{{ $amende->montant }}"
                                                            data-bs-toggle="modal" data-bs-target="#paiementModal">
                                                            <i class="fas fa-credit-card me-1"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                                            <i class="fas fa-check me-1"></i> Payée
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <td colspan="3" class="text-end fw-bold">Total à payer :</td>
                                            <td colspan="3" class="fw-bold">
                                                $
                                                {{ number_format($amendes->where('est_payee', false)->sum('montant'), 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Paiement avec PayPal -->
        <div class="modal fade" id="paiementModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-credit-card me-2"></i> Paiement de l'amende
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="paypalForm" action="{{ route('frontOffice.paypal.payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="amende_id" id="amende_id" value="">
                        <input type="hidden" name="amount" id="amount" value="">
                        
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h4>Montant à payer : <span id="montantAPayer" class="text-primary fw-bold">0,00 €</span></h4>
                                <p class="text-muted">Vous allez être redirigé vers PayPal pour effectuer le paiement en toute sécurité.</p>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="paypalButton">
                                    <i class="fab fa-paypal me-2"></i> Payer avec PayPal
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <i class="fab fa-paypal fa-2x text-primary"></i>
                                <p class="small text-muted mt-2">Paiement sécurisé par PayPal</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Attendre que le DOM soit complètement chargé
            document.addEventListener('DOMContentLoaded', function() {
                // Variables globales
                let currentAmendeId = null;
                let currentMontant = 0;
                
                // Gestionnaire d'événement pour les boutons de paiement
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.btn-payer')) return;
                    const btn = e.target.closest('.btn-payer');
                    currentAmendeId = btn.dataset.id;
                    currentMontant = parseFloat(btn.dataset.montant);
                    
                    // Mettre à jour les champs cachés du formulaire
                    document.getElementById('amende_id').value = currentAmendeId;
                    document.getElementById('amount').value = currentMontant;
                    
                    // Mettre à jour l'affichage du montant dans la modale
                    document.getElementById('montantAPayer').textContent = currentMontant.toFixed(2) + ' €';
                });
                
                // Gestion de la soumission du formulaire PayPal
                const paypalForm = document.getElementById('paypalForm');
                if (paypalForm) {
                    paypalForm.addEventListener('submit', async function(e) {
                        e.preventDefault(); // Empêcher la soumission normale du formulaire
                        
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const formData = new FormData(this);
                        
                        // Vérifier que les champs requis sont présents
                        const amendeId = document.getElementById('amende_id').value;
                        const amount = document.getElementById('amount').value;
                        
                        if (!amendeId || !amount) {
                            showAlert('danger', 'Erreur: Données de paiement manquantes');
                            return;
                        }
                        
                        // Désactiver le bouton pour éviter les doubles soumissions
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Préparation du paiement...';
                        }
                        
                        try {
                            // Envoyer les données au serveur
                            const response = await fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams(formData).toString()
                            });
                            
                            const data = await response.json();
                            
                            if (!response.ok) {
                                throw new Error(data.message || 'Erreur lors de la préparation du paiement');
                            }
                            
                            // Fermer la modale avant la redirection
                            const modal = bootstrap.Modal.getInstance(document.getElementById('paiementModal'));
                            if (modal) {
                                modal.hide();
                            }
                            
                            // Afficher un message à l'utilisateur
                            showAlert('info', 'Redirection vers PayPal...');
                            
                            // Ouvrir PayPal dans un nouvel onglet après un court délai
                            setTimeout(() => {
                                const newWindow = window.open(data.redirect_url, '_blank');
                                
                                // Si la fenêtre a été bloquée par le bloqueur de popup
                                if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
                                    // Afficher un message à l'utilisateur
                                    showAlert('warning', 'Votre navigateur a bloqué l\'ouverture de PayPal. Veuillez cliquer sur le bouton ci-dessous pour accéder à la page de paiement.');
                                    
                                    // Ajouter un bouton pour ouvrir manuellement PayPal
                                    const alertDiv = document.createElement('div');
                                    alertDiv.className = 'alert alert-warning mt-3';
                                    alertDiv.innerHTML = `
                                        <p>Si la page de paiement ne s'est pas ouverte, veuillez cliquer sur ce bouton :</p>
                                        <a href="${data.redirect_url}" target="_blank" class="btn btn-warning">
                                            <i class="fab fa-paypal me-2"></i> Ouvrir la page de paiement PayPal
                                        </a>
                                    `;
                                    
                                    // Ajouter l'alerte après le message existant
                                    const existingAlert = document.querySelector('.alert-info');
                                    if (existingAlert) {
                                        existingAlert.after(alertDiv);
                                    }
                                }
                            }, 500);
                            
                        } catch (error) {
                            console.error('Erreur:', error);
                            showAlert('danger', 'Erreur: ' + (error.message || 'Une erreur est survenue lors de la préparation du paiement'));
                            
                            // Réactiver le bouton en cas d'erreur
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fab fa-paypal me-2"></i> Payer avec PayPal';
                            }
                        }
                    }); // Fin de l'écouteur d'événements
                } // Fin du if (paypalForm)
                
                // Fonction pour afficher les alertes
                function showAlert(type, message) {
                    // Créer l'élément d'alerte
                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    // Afficher l'alerte en haut de la page
                    const container = document.querySelector('.container');
                    if (container) {
                        // Insérer l'alerte au début du conteneur
                        container.insertBefore(alertDiv, container.firstChild);
                        
                        // Initialiser l'alerte Bootstrap
                        const alert = new bootstrap.Alert(alertDiv);
                        
                        // Supprimer l'alerte après 5 secondes
                        setTimeout(() => {
                            alert.close();
                        }, 5000);
                    } else {
                        // Si le conteneur n'est pas trouvé, utiliser alert() natif
                        alert(`[${type.toUpperCase()}] ${message}`);
                    }
                    
                    // Retourner l'élément d'alerte pour référence ultérieure
                    return alertDiv;
                }
            });
        </script>
        
        <!-- Script pour l'initialisation de Bootstrap -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Vérifier si Bootstrap est disponible
                if (typeof bootstrap !== 'undefined') {
                    // Activer les tooltips
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                        try {
                            new bootstrap.Tooltip(tooltipTriggerEl);
                        } catch (e) {
                            console.error('Erreur d\'initialisation du tooltip:', e);
                        }
                    });
                    
                    // Activer les popovers
                    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                    popoverTriggerList.forEach(function (popoverTriggerEl) {
                        try {
                            new bootstrap.Popover(popoverTriggerEl);
                        } catch (e) {
                            console.error('Erreur d\'initialisation du popover:', e);
                        }
                    });
                } else {
                    console.warn('Bootstrap non chargé. Les tooltips et popovers ne seront pas disponibles.');
                }
            });
        </script>
    </div> <!-- Fin du conteneur principal -->
@endsection
