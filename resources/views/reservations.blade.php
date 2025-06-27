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
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target="#ajouterReservationModal">
                                    <i class="fas fa-plus-circle mr-1"></i> Nouvelle Réservation
                                </button>

                                <!-- Filtres -->
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter mr-1"></i> Filtres
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-filter="all">Toutes</a>
                                        <a class="dropdown-item" href="#" data-filter="confirmee">Confirmées</a>
                                        <a class="dropdown-item" href="#" data-filter="en_attente">En attente</a>
                                        <a class="dropdown-item" href="#" data-filter="annulee">Annulées</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 text-right">
                                <div class="input-group" style="max-width: 300px; float: right;">
                                    <input type="text" id="date-range-picker" class="form-control"
                                        placeholder="Période...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="filter-date">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Ajouter Réservation -->
                        <div class="modal fade" id="ajouterReservationModal" tabindex="-1" role="dialog"
                            aria-labelledby="ajouterReservationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <form action="{{ url('amin.reservations.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="ajouterReservationModalLabel">Nouvelle
                                                Réservation</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Fermer">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Utilisateur</label>
                                                    <select name="utilisateur_id" class="form-control" required>
                                                        <option value="">Sélectionner un utilisateur</option>
                                                        {{-- @foreach ($utilisateurs as $utilisateur)
                                                            <option value="{{ $utilisateur->id }}">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Ouvrage</label>
                                                    <select name="ouvrage_id" class="form-control" required>
                                                        <option value="">Sélectionner un ouvrage</option>
                                                        {{-- @foreach ($ouvrages as $ouvrage)
                                                            <option value="{{ $ouvrage->id }}">{{ $ouvrage->titre }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Date de réservation</label>
                                                    <input type="text" name="date_reservation"
                                                        class="form-control datepicker" required>
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
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annuler</button>
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
                                        <th>Utilisateur</th>
                                        <th>Ouvrage</th>
                                        <th>Date Réservation</th>
                                        <th>Statut</th>
                                        <th>Créée le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservations as $reservation)
                                        <tr data-status="{{ $reservation->statut }}">
                                            <td>#RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        {{ $reservation->utilisateur->prenom }}
                                                        {{ $reservation->utilisateur->nom }}
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ $reservation->utilisateur->email }}</small>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $reservation->ouvrage->titre }}
                                            </td>
                                            <td>
                                                {{ $reservation->date_reservation->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'validee' => 'success',
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
                                                    <!-- Bouton Voir détails -->
                                                    <button type="button" class="btn btn-outline-info mx-1"
                                                        title="Voir détails" data-toggle="modal"
                                                        data-target="#viewReservationModal-{{ $reservation->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    @if ($reservation->statut == 'en_attente')
                                                        <!-- Modal de validation -->
                                                        <div class="modal fade"
                                                            id="validateReservationModal-{{ $reservation->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-success text-white">
                                                                        <h5 class="modal-title">Valider la réservation
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Valider la réservation
                                                                            #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                                                            ?</p>
                                                                        <div class="alert alert-info mt-3">
                                                                            <i class="fas fa-info-circle mr-2"></i>
                                                                            Cela créera un nouvel emprunt et
                                                                            décrémentera le stock.
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Annuler</button>
                                                                        <form
                                                                            action="{{ route('admin.reservations.valider', $reservation->id) }}"
                                                                            method="POST" style="display: inline;">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="btn btn-success">
                                                                                <i class="fas fa-check mr-1"></i>
                                                                                Valider
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Bouton Valider -->
                                                        <button type="button" class="btn btn-outline-success mx-1"
                                                            title="Valider" data-toggle="modal"
                                                            data-target="#validateReservationModal-{{ $reservation->id }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>

                                                        <!-- Modal d'annulation -->
                                                        <div class="modal fade"
                                                            id="cancelReservationModal-{{ $reservation->id }}"
                                                            tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title">Annuler la réservation
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Annuler la réservation
                                                                            #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                                                            ?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Non</button>
                                                                        <form
                                                                            action="{{ route('admin.reservations.annuler', $reservation->id) }}"
                                                                            method="POST" style="display: inline;">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="btn btn-danger">
                                                                                <i class="fas fa-times mr-1"></i> Oui,
                                                                                annuler
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Bouton Annuler -->
                                                        <button type="button" class="btn btn-outline-danger mx-1"
                                                            title="Annuler" data-toggle="modal"
                                                            data-target="#cancelReservationModal-{{ $reservation->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    {{-- @elseif($reservation->statut == 'validee')
                                                        <span class="badge badge-success">Validée</span>
                                                    @elseif($reservation->statut == 'annulee')
                                                        <span class="badge badge-danger">Annulée</span> --}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Affichage de <b>{{ $reservations->firstItem() }}</b> à
                                <b>{{ $reservations->lastItem() }}</b> sur <b>{{ $reservations->total() }}</b>
                                réservations
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
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                locale: "fr"
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

                if (status === 'all') {
                    $('#reservations-table tbody tr').show();
                } else {
                    $('#reservations-table tbody tr').hide();
                    $(`#reservations-table tbody tr[data-status="${status}"]`).show();
                }
            });

            // Confirmation/Annulation des réservations
            $('.confirm-reservation').click(function() {
                const reservationId = $(this).data('id');
                if (confirm('Confirmer cette réservation ?')) {
                    $.ajax({
                        url: `/admin/reservations/${reservationId}/confirm`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH'
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });

            $('.cancel-reservation').click(function() {
                const reservationId = $(this).data('id');
                if (confirm('Annuler cette réservation ?')) {
                    $.ajax({
                        url: `/admin/reservations/${reservationId}/cancel`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH'
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
