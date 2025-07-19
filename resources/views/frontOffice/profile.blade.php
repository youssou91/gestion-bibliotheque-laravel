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

                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_password"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="newPassword" name="new_password"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="confirmPassword"
                                    name="new_password_confirmation" required>
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
                                                        <button class="btn btn-sm btn-outline-primary payer-amende"
                                                            data-bs-toggle="modal" data-bs-target="#paiementModal"
                                                            data-amende-id="{{ $amende->id }}"
                                                            data-montant="{{ $amende->montant }}">
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
                        <h5 class="modal-title">Paiement d'amende</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <form id="paiementForm">
                            @csrf
                            <input type="hidden" id="amende_id" name="amende_id">

                            <div class="mb-4 text-center">
                                <h6 class="text-muted">Montant à payer</h6>
                                <h3 class="fw-bold text-primary" id="montantAmende"></h3>
                            </div>

                            <ul class="nav nav-tabs mb-4" id="paymentMethodTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="paypal-tab" data-bs-toggle="tab"
                                        data-bs-target="#paypal-tab-pane" type="button" role="tab">
                                        <i class="fab fa-paypal me-2"></i>PayPal
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="other-tab" data-bs-toggle="tab"
                                        data-bs-target="#other-tab-pane" type="button" role="tab">
                                        <i class="fas fa-credit-card me-2"></i>Autre méthode
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="paymentMethodTabsContent">
                                <!-- Onglet PayPal -->
                                <div class="tab-pane fade show active" id="paypal-tab-pane" role="tabpanel">
                                    <div id="paypal-button-container" class="mt-3"></div>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            Vous serez redirigé vers PayPal pour compléter le paiement
                                        </small>
                                    </div>
                                </div>

                                <!-- Onglet Autres méthodes -->
                                <div class="tab-pane fade" id="other-tab-pane" role="tabpanel">
                                    <form method="POST" action="{{ route('frontOffice.payerAmende') }}">
                                        @csrf
                                        <input type="hidden" name="amende_id" id="other_amende_id">
                                        <div class="mb-3">
                                            <label class="form-label">Méthode de paiement</label>
                                            <select class="form-select" name="methode_paiement" required>
                                                <option value="">Sélectionner...</option>
                                                <option value="carte">Carte bancaire</option>
                                                <option value="especes">Espèces</option>
                                                <option value="cheque">Chèque</option>
                                            </select>
                                        </div>

                                        <div id="carteFields" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Numéro de carte</label>
                                                <input type="text" class="form-control" name="numero_carte"
                                                    placeholder="1234 5678 9012 3456">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Date d'expiration</label>
                                                    <input type="text" class="form-control" name="expiration_carte"
                                                        placeholder="MM/AA">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">CVV</label>
                                                    <input type="text" class="form-control" name="cvv_carte"
                                                        placeholder="123">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-4">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                                        </div>
                                    </form>
                                </div>
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

            /* Pour la carte */
            .card-member {
                border: 1px solid #dee2e6;
                border-radius: 10px;
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                max-width: 350px;
                margin: 0 auto;
            }

            .card-member .card-header {
                border-bottom: 2px solid var(--bs-primary);
            }

            .card-member .member-info {
                border-top: 1px dashed #dee2e6;
                border-bottom: 1px dashed #dee2e6;
                padding: 15px 0;
            }

            /* Style pour le modal des amendes */
            #amendesModal .modal-header {
                border-bottom: 2px solid var(--bs-warning);
            }

            #amendesModal .table-hover tbody tr:hover {
                background-color: rgba(255, 193, 7, 0.1);
            }

            .badge.bg-warning {
                color: #212529;
            }

            /* Style pour les champs carte */
            #carteFields input {
                background-color: #f8f9fa;
            }

            /* Style pour les onglets de paiement */
            .nav-tabs .nav-link {
                color: #495057;
            }

            .nav-tabs .nav-link.active {
                color: #0d6efd;
                font-weight: 500;
            }
        </style>
    {{-- @endsection --}}

    {{-- @push('scripts') --}}
        <!-- SDK PayPal -->
        <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=USD&components=buttons">
        </script>

        <script>
            $(document).ready(function() {
                let paypalButtonsRendered = false;
                let currentAmendeId = null;
                let currentMontant = null;

                // Lorsqu'on clique sur un bouton de paiement
                $('.payer-amende').click(function() {
                    currentAmendeId = $(this).data('amende-id');
                    currentMontant = $(this).data('montant');

                    $('#amende_id').val(currentAmendeId);
                    $('#other_amende_id').val(currentAmendeId);
                    $('#montantAmende').text('$' + currentMontant);

                    // Réinitialiser PayPal si déjà rendu
                    if (paypalButtonsRendered) {
                        $('#paypal-button-container').empty();
                        paypalButtonsRendered = false;
                    }
                });

                // Afficher/masquer les champs carte bancaire
                $('select[name="methode_paiement"]').change(function() {
                    if ($(this).val() === 'carte') {
                        $('#carteFields').show();
                    } else {
                        $('#carteFields').hide();
                    }
                });

                // Initialisation des boutons PayPal lorsque l'onglet est visible
                $('#paypal-tab').on('shown.bs.tab', function() {
                    if (!paypalButtonsRendered && currentAmendeId && currentMontant) {
                        renderPayPalButtons();
                        paypalButtonsRendered = true;
                    }
                });

                // Ajout : Initialisation à chaque ouverture du modal
                $('#paiementModal').on('shown.bs.modal', function () {
                    // Toujours réinitialiser le conteneur et l'état
                    $('#paypal-button-container').empty();
                    paypalButtonsRendered = false;
                    if (currentAmendeId && currentMontant) {
                        renderPayPalButtons();
                        paypalButtonsRendered = true;
                    } else {
                        $('#paypal-button-container').html('<div class="alert alert-warning">Aucune amende sélectionnée pour le paiement.</div>');
                    }
                });

                // Affiche un message si on clique sur l’onglet PayPal sans amende sélectionnée
                $('#paypal-tab').on('shown.bs.tab', function() {
                    $('#paypal-button-container').empty();
                    paypalButtonsRendered = false;
                    if (currentAmendeId && currentMontant) {
                        renderPayPalButtons();
                        paypalButtonsRendered = true;
                    } else {
                        $('#paypal-button-container').html('<div class="alert alert-warning">Aucune amende sélectionnée pour le paiement.</div>');
                    }
                });

                function renderPayPalButtons() {
                    paypal.Buttons({
                        style: {
                            layout: 'vertical',
                            color: 'blue',
                            shape: 'rect',
                            label: 'paypal'
                        },
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: currentMontant,
                                        currency_code: 'USD'
                                    },
                                    description: 'Paiement amende #' + currentAmendeId,
                                    custom_id: currentAmendeId
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                // Envoyer les données au serveur
                                $.ajax({
                                    url: '{{ route('frontOffice.payerAmendePaypal') }}',
                                    method: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        amende_id: currentAmendeId,
                                        paypal_order_id: data.orderID,
                                        paypal_payer_id: details.payer.payer_id
                                    },
                                    success: function(response) {
                                        $('#paiementModal').modal('hide');
                                        showSuccessAlert(
                                            'Paiement effectué avec succès via PayPal'
                                            );
                                        refreshAmendesList();
                                    },
                                    error: function(xhr) {
                                        console.error(xhr);
                                        alert(
                                            'Erreur lors de la validation du paiement');
                                    }
                                });
                            });
                        },
                        onError: function(err) {
                            console.error('Erreur PayPal:', err);
                            alert('Une erreur est survenue avec PayPal');
                        }
                    }).render('#paypal-button-container');
                }

                // Après soumission du formulaire (pour les autres méthodes)
                $('#other-tab-pane form').submit(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#paiementModal').modal('hide');
                            showSuccessAlert(response.message);
                            refreshAmendesList();
                        },
                        error: function(xhr) {
                            alert('Une erreur est survenue lors du paiement');
                        }
                    });
                });

                function showSuccessAlert(message) {
                    $('.container.py-5').prepend(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            `);
                }

                function refreshAmendesList() {
                    setTimeout(() => {
                        $('#amendesModal').modal('hide');
                        setTimeout(() => $('#amendesModal').modal('show'), 500);
                    }, 1000);
                }
            });
        </script>
    {{-- @endpush --}}
@endsection
