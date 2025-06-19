<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mt-4"><i class="fas fa-calendar-check mr-2"></i>Gestion des Réservations</h1>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <!-- Carte Total Réservations -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-primary">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-primary text-white mb-2">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['total'] }}</h3>
                                        <p class="text-muted mb-0">Total Réservations</p>
                                        <div class="text-xs text-primary mt-1">
                                            <i class="fas fa-calendar-day"></i> {{ now()->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Carte Réservations Confirmées -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-success">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-success text-white mb-2">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['confirmees'] }}</h3>
                                        <p class="text-muted mb-0">Confirmées</p>
                                        <div class="text-xs text-success mt-1">
                                            {{ $stats['total'] > 0 ? round(($stats['confirmees'] / $stats['total']) * 100, 1) : 0 }}%
                                            du total
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Carte Réservations En Attente -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-warning">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-warning text-white mb-2">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['en_attente'] }}</h3>
                                        <p class="text-muted mb-0">En Attente</p>
                                        <div class="text-xs text-warning mt-1">
                                            {{ $stats['total'] > 0 ? round(($stats['en_attente'] / $stats['total']) * 100, 1) : 0 }}%
                                            du total
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Carte Réservations Annulées -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-danger">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-danger text-white mb-2">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $stats['annulees'] }}</h3>
                                        <p class="text-muted mb-0">Annulées</p>
                                        <div class="text-xs text-danger mt-1">
                                            <i class="fas fa-chart-line"></i> Taux d'annulation
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
                        </style>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#ajouterReservationModal">
                                    <i class="fas fa-plus-circle mr-1"></i> Nouvelle Réservation
                                </button>
                                
                                <!-- Filtres -->
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter mr-1"></i> Filtres
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-filter="all">Toutes</a>
                                        <a class="dropdown-item" href="#" data-filter="confirmees">Confirmées</a>
                                        <a class="dropdown-item" href="#" data-filter="en_attente">En attente</a>
                                        <a class="dropdown-item" href="#" data-filter="annulees">Annulées</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 text-right">
                                <div class="input-group" style="max-width: 300px; float: right;">
                                    <input type="text" id="date-range-picker" class="form-control" placeholder="Période...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="filter-date">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Ajouter Réservation -->
                        <div class="modal fade" id="ajouterReservationModal" tabindex="-1" role="dialog" aria-labelledby="ajouterReservationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <form action="{{ url('admin.reservations.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="ajouterReservationModalLabel">Nouvelle Réservation</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Client</label>
                                                    <select name="client_id" class="form-control" required>
                                                        <option value="">Sélectionner un client</option>
                                                        {{-- @foreach($clients as $client)
                                                            <option value="{{ $client->id }}">{{ $client->prenom }} {{ $client->nom }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Service</label>
                                                    <select name="service_id" class="form-control" required>
                                                        <option value="">Sélectionner un service</option>
                                                        {{-- @foreach($services as $service)
                                                            <option value="{{ $service->id }}">{{ $service->nom }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Date</label>
                                                    <input type="text" name="date" class="form-control datepicker" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Heure</label>
                                                    <input type="text" name="heure" class="form-control timepicker" required>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Statut</label>
                                                <select name="statut" class="form-control" required>
                                                    <option value="en_attente">En attente</option>
                                                    <option value="confirmee">Confirmée</option>
                                                    <option value="annulee">Annulée</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Notes</label>
                                                <textarea name="notes" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tableau des réservations -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="reservations-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Date/Heure</th>
                                        <th>Statut</th>
                                        <th>Créée le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                    <tr data-status="{{ $reservation->statut }}">
                                        <td>#RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{-- @if($reservation->client->photo)
                                                    <img src="{{ asset('assets/img/' . $reservation->client->photo) }}" 
                                                         class="rounded-circle mr-2" width="30" height="30" 
                                                         alt="Photo client">
                                                @else
                                                    <div class="avatar-placeholder rounded-circle mr-2 bg-secondary text-white d-flex align-items-center justify-content-center"
                                                         style="width:30px;height:30px;">
                                                        {{ substr($reservation->client->prenom, 0, 1) }}{{ substr($reservation->client->nom, 0, 1) }}
                                                    </div>
                                                @endif --}}
                                                <span>
                                                    prenom: 
                                                    {{-- {{ $reservation->client->prenom }} --}}
                                                    <br>
                                                    nom: 
                                                    {{-- {{ $reservation->client->nom }} --}}
                                                    <br>
                                                    {{-- {{ $reservation->client->prenom }} {{ $reservation->client->nom }} --}}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            nom: 
                                            {{-- {{ $reservation->service->nom }} --}}
                                        </td>
                                        <td>
                                            date: 
                                            {{-- {{ $reservation->date->format('d/m/Y') }} --}}
                                            {{-- {{ $reservation->date->format('d/m/Y') }}  --}}
                                            <span class="badge badge-light">{{ $reservation->heure }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'confirmee' => 'success',
                                                    'en_attente' => 'warning',
                                                    'annulee' => 'danger',
                                                ][$reservation->statut];
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                            </span>
                                        </td>
                                        <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center" style="width: 150px">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- Bouton Modifier -->
                                                <button type="button" class="btn btn-outline-warning mx-1"
                                                    title="Modifier" data-toggle="modal"
                                                    data-target="#editReservationModal-{{ $reservation->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <!-- Bouton Voir détails -->
                                                <button type="button" class="btn btn-outline-info mx-1"
                                                    title="Voir détails" data-toggle="modal"
                                                    data-target="#viewReservationModal-{{ $reservation->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <!-- Bouton Confirmer/Annuler -->
                                                @if($reservation->statut == 'en_attente')
                                                    <button type="button" class="btn btn-outline-success mx-1 confirm-reservation"
                                                        title="Confirmer" data-id="{{ $reservation->id }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger mx-1 cancel-reservation"
                                                        title="Annuler" data-id="{{ $reservation->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($reservation->statut == 'confirmee')
                                                    <button type="button" class="btn btn-outline-danger mx-1 cancel-reservation"
                                                        title="Annuler" data-id="{{ $reservation->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal pour voir les détails -->
                                    <div class="modal fade" id="viewReservationModal-{{ $reservation->id }}" tabindex="-1" role="dialog" 
                                         aria-labelledby="viewReservationModalLabel-{{ $reservation->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewReservationModalLabel-{{ $reservation->id }}">
                                                        Réservation #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6><i class="fas fa-user mr-2"></i> Client</h6>
                                                            <p class="ml-4">
                                                                <strong>
                                                                    prenom: 
                                                                    {{-- {{ $reservation->client->prenom }} --}}
                                                                    <br>
                                                                    nom: 
                                                                    {{-- {{ $reservation->client->nom }} --}}
                                                                    {{-- {{ $reservation->client->prenom }} {{ $reservation->client->nom }} --}}
                                                                </strong><br>
                                                                email: 
                                                                {{-- {{ $reservation->client->email ?? 'Non renseigné' }} --}}
                                                                {{-- Non renseigné si pas d'email --}}
                                                                {{-- Si email, afficher l'email --}}
                                                                {{-- Si pas d'email, afficher "Non renseigné" --}}
                                                                {{-- Pour l'instant, on ne l'affiche pas --}}
                                                                {{-- {{ $reservation->client->email }} --}}
                                                                <br>
                                                                telephone: 
                                                                {{-- {{ $reservation->client->telephone ?? 'Non renseigné' }} --}}
                                                                {{-- Non renseigné si pas de téléphone --}}
                                                                {{-- Si téléphone, afficher le téléphone --}}
                                                                {{-- Si pas de téléphone, afficher "Non renseigné" --}}
                                                                {{-- Pour l'instant, on ne l'affiche pas --}}
                                                                {{-- {{ $reservation->client->telephone ?? 'Non renseigné' }} --}}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6><i class="fas fa-calendar-check mr-2"></i> Détails</h6>
                                                            <p class="ml-4">
                                                                <strong>Date:</strong> 
                                                                {{-- {{ $reservation->date->format('d/m/Y') }} --}}
                                                                <br>
                                                                <strong>Heure:</strong> {{ $reservation->heure }}<br>
                                                                <strong>Statut:</strong> 
                                                                <span class="badge badge-{{ $statusClass }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <h6><i class="fas fa-concierge-bell mr-2"></i> Service</h6>
                                                            <div class="card bg-light p-3">
                                                                <div class="d-flex justify-content-between">
                                                                    <div>
                                                                        <h5 class="mb-1">
                                                                            nom: 
                                                                            {{-- {{ $reservation->service->nom }} --}}
                                                                            {{-- Si le nom du service est vide, afficher "Service non renseigné" --}}
                                                                            {{-- Si le nom du service est renseigné, afficher le nom --}}
                                                                            {{-- Pour l'instant, on ne l'affiche pas --}}
                                                                            {{-- Si le nom du service est renseigné, afficher le nom --}}
                                                                            {{-- Si le nom du service n'est pas renseigné, afficher "Service non renseigné" --}}
                                                                            {{-- Pour l'instant, on ne l'affiche pas --}}
                                                                            {{-- {{ $reservation->service->nom }} --}}
                                                                            {{--  --}}
                                                                        </h5>
                                                                        <p class="mb-1">
                                                                            prenom: 
                                                                            {{-- {{ $reservation->service->prenom }} --}}
                                                                            {{-- {{ $reservation->service->description }} --}}
                                                                            {{--  --}}
                                                                        </p>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <h5 class="mb-1">
                                                                            {{-- {{ number_format($reservation->service->prix, 2) }}  --}}
                                                                            €</h5>
                                                                        <p class="mb-1">Durée: 
                                                                            {{-- {{ $reservation->service->duree }} --}}
                                                                             min</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($reservation->notes)
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <h6><i class="fas fa-sticky-note mr-2"></i> Notes</h6>
                                                            <div class="card bg-light p-3">
                                                                {{ $reservation->notes }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" 
                                                            data-toggle="modal" data-target="#editReservationModal-{{ $reservation->id }}">
                                                        <i class="fas fa-edit mr-1"></i> Modifier
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal pour modifier -->
                                    <div class="modal fade" id="editReservationModal-{{ $reservation->id }}" tabindex="-1" role="dialog" 
                                         aria-labelledby="editReservationModalLabel-{{ $reservation->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="{{ url('admin.reservations.update', $reservation->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editReservationModalLabel-{{ $reservation->id }}">
                                                            Modifier Réservation #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Client</label>
                                                                <select name="client_id" class="form-control" required>
                                                                    <option value="">Sélectionner un client</option>
                                                                    {{-- Décommenter et utiliser les clients si nécessaire --}}
                                                                    {{-- @foreach($clients as $client)
                                                                        <option value="{{ $client->id }}" {{ $client->id == $reservation->client_id ? 'selected' : '' }}>
                                                                            {{ $client->prenom }} {{ $client->nom }}
                                                                        </option>
                                                                    @endforeach --}}
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Service</label>
                                                                <select name="service_id" class="form-control" required>
                                                                    {{-- @foreach($services as $service)
                                                                        <option value="{{ $service->id }}" {{ $service->id == $reservation->service_id ? 'selected' : '' }}>
                                                                            {{ $service->nom }}
                                                                        </option>
                                                                    @endforeach --}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Date</label>
                                                                <input type="text" name="date" class="form-control datepicker" 
                                                                       value="
                                                                       {{-- {{ $reservation->date->format('Y-m-d') }} --}}
                                                                       " required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Heure</label>
                                                                <input type="text" name="heure" class="form-control timepicker" 
                                                                       value="
                                                                       {{-- {{ $reservation->heure }} --}}
                                                                        " required>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label>Statut</label>
                                                            <select name="statut" class="form-control" required>
                                                                <option value="en_attente" {{ $reservation->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                                <option value="confirmee" {{ $reservation->statut == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                                                <option value="annulee" {{ $reservation->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label>Notes</label>
                                                            <textarea name="notes" class="form-control" rows="3">{{ $reservation->notes }}</textarea>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Affichage de <b>{{ $reservations->firstItem() }}</b> à <b>{{ $reservations->lastItem() }}</b> sur <b>{{ $reservations->total() }}</b> réservations
                            </div>
                            <div>
                                {{ $reservations->links() }}
                            </div>
                        </div>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialisation des datepickers
            $('.datepicker').flatpickr({
                dateFormat: "Y-m-d",
                locale: "fr"
            });
            
            $('.timepicker').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
            
            // Picker pour la période
            $('#date-range-picker').flatpickr({
                mode: "range",
                dateFormat: "d/m/Y",
                locale: "fr"
            });
            
            // Filtrage par statut
            $('[data-filter]').click(function(e) {
                e.preventDefault();
                const status = $(this).data('filter');
                
                if(status === 'all') {
                    $('#reservations-table tbody tr').show();
                } else {
                    $('#reservations-table tbody tr').hide();
                    $(`#reservations-table tbody tr[data-status="${status}"]`).show();
                }
            });
            
            // Confirmation/Annulation des réservations
            $('.confirm-reservation').click(function() {
                const reservationId = $(this).data('id');
                if(confirm('Confirmer cette réservation ?')) {
                    $.ajax({
                        url: `/admin/reservations/${reservationId}/confirm`,
                        method: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });
            
            $('.cancel-reservation').click(function() {
                const reservationId = $(this).data('id');
                if(confirm('Annuler cette réservation ?')) {
                    $.ajax({
                        url: `/admin/reservations/${reservationId}/cancel`,
                        method: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html> 