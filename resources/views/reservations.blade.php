<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
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
                                        <p class="text-muted mb-0">Validées</p>
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

                        <div class="row mb-3 mt-4">
                            {{-- <div class="col-md-4">
                                <label for="date-range-picker">Filtrer par date :</label>
                                <input type="text" id="date-range-picker" class="form-control datepicker"
                                    placeholder="Sélectionner une période" />
                            </div> --}}
                            <div class="col-md-4">
                                <label for="status-filter">Filtrer par statut :</label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-primary active" data-filter="all">
                                        <input type="radio" name="status-filter" value="all" checked> Tous
                                    </label>
                                    <label class="btn btn-outline-success" data-filter="validee">
                                        <input type="radio" name="status-filter" value="validee"> Validées
                                    </label>
                                    <label class="btn btn-outline-warning" data-filter="en_attente">
                                        <input type="radio" name="status-filter" value="en_attente"> En Attente
                                    </label>
                                    <label class="btn btn-outline-danger" data-filter="annulee">
                                        <input type="radio" name="status-filter" value="annulee"> Annulées
                                    </label>
                                </div>
                            </div>
                            <!-- Tableau des réservations -->
                            <div class="table-responsive ">
                                <table class="table table-hover table-bordered" id="reservations-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Utilisateur</th>
                                            <th>Ouvrage</th>
                                            <th>Statut</th>
                                            <th>Date Réservation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reservations as $reservation)
                                            <tr data-status="{{ $reservation->statut }}">
                                                <td class="text-center" style="width: 150px">
                                                    #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                                </td>
                                                <td class="text-center" style="width: 200px">
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
                                                <td class="text-center" style="width: 250px">
                                                    {{ $reservation->ouvrage->titre }}
                                                </td>
                                                <td class="text-center" style="width: 150px">
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
                                                <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center" style="width: 150px">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- Bouton Voir détails -->
                                                        <button type="button" class="btn btn-outline-info mx-1"
                                                            title="Voir détails" data-toggle="modal"
                                                            data-target="#viewReservationModal-{{ $reservation->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <!-- Modal pour voir les détails -->
                                                        <div class="modal fade"
                                                            id="viewReservationModal-{{ $reservation->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="viewReservationModalLabel-{{ $reservation->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary text-white">
                                                                        <h5 class="modal-title"
                                                                            id="viewReservationModalLabel-{{ $reservation->id }}">
                                                                            <i class="fas fa-calendar-check mr-2"></i>
                                                                            Réservation
                                                                            #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="close text-white"
                                                                            data-dismiss="modal" aria-label="Fermer">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <!-- Section Client -->
                                                                            <div class="col-md-6 mb-4">
                                                                                <div class="card h-100">
                                                                                    <div class="card-header bg-light">
                                                                                        <h6 class="mb-0"><i
                                                                                                class="fas fa-user mr-2"></i>Informations
                                                                                            Client</h6>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="d-flex align-items-center mb-3">
                                                                                            <div class="avatar bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center"
                                                                                                style="width: 50px; height: 50px; font-size: 1.2rem;">
                                                                                                {{ substr($reservation->utilisateur->prenom, 0, 1) }}{{ substr($reservation->utilisateur->nom, 0, 1) }}
                                                                                            </div>
                                                                                            <div>
                                                                                                <h5 class="mb-1">
                                                                                                    {{ $reservation->utilisateur->prenom }}
                                                                                                    {{ $reservation->utilisateur->nom }}
                                                                                                </h5>
                                                                                                <p
                                                                                                    class="text-muted mb-1">
                                                                                                    {{ $reservation->utilisateur->email }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <ul
                                                                                            class="list-group list-group-flush">
                                                                                            <li
                                                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <span><i
                                                                                                        class="fas fa-phone mr-2"></i>Téléphone</span>
                                                                                                <span>{{ $reservation->utilisateur->telephone ?? 'Non renseigné' }}</span>
                                                                                            </li>
                                                                                            <li
                                                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <span><i
                                                                                                        class="fas fa-calendar-alt mr-2"></i>Membre
                                                                                                    depuis</span>
                                                                                                <span>{{ $reservation->utilisateur->created_at->format('d/m/Y') }}</span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Section Réservation -->
                                                                            <div class="col-md-6 mb-4">
                                                                                <div class="card h-100">
                                                                                    <div class="card-header bg-light">
                                                                                        <h6 class="mb-0"><i
                                                                                                class="fas fa-info-circle mr-2"></i>Détails
                                                                                            Réservation</h6>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <ul
                                                                                            class="list-group list-group-flush">
                                                                                            <li
                                                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <span><i
                                                                                                        class="fas fa-calendar-day mr-2"></i>Date
                                                                                                    réservation</span>
                                                                                                <span>{{ $reservation->date_reservation->format('d/m/Y') }}</span>
                                                                                            </li>
                                                                                            <li
                                                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <span><i
                                                                                                        class="fas fa-clock mr-2"></i>Statut</span>
                                                                                                <span
                                                                                                    class="badge badge-{{ $statusClass }}">
                                                                                                    {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                                                                                </span>
                                                                                            </li>
                                                                                            <li
                                                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <span><i
                                                                                                        class="fas fa-calendar-plus mr-2"></i>Date
                                                                                                    création</span>
                                                                                                <span>{{ $reservation->created_at->format('d/m/Y') }}</span>
                                                                                            </li>
                                                                                            <li
                                                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <span><i
                                                                                                        class="fas fa-calendar-edit mr-2"></i>Dernière
                                                                                                    mise à jour</span>
                                                                                                <span>{{ $reservation->updated_at->format('d/m/Y') }}</span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Section Ouvrage -->
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-light">
                                                                                        <h6 class="mb-0"><i
                                                                                                class="fas fa-book mr-2"></i>Détails
                                                                                            de l'ouvrage</h6>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                @if ($reservation->ouvrage->photo)
                                                                                                    <div
                                                                                                        class="text-center">
                                                                                                        <img src="{{ asset('assets/img/' . $reservation->ouvrage->photo) }}"
                                                                                                            class="img-fluid rounded"
                                                                                                            style="max-height: 150px; width: auto;"
                                                                                                            alt="Couverture de l'ouvrage">
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                                                                        style="height: 150px; width: 100%;">
                                                                                                        <i
                                                                                                            class="fas fa-book-open fa-3x text-muted"></i>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="col-md-8">
                                                                                                <h4>{{ $reservation->ouvrage->titre }}
                                                                                                </h4>
                                                                                                <p class="text-muted">
                                                                                                    {{ $reservation->ouvrage->auteur }}
                                                                                                </p>

                                                                                                <div class="row mt-3">
                                                                                                    <div
                                                                                                        class="col-md-6">
                                                                                                        <p
                                                                                                            class="mb-1">
                                                                                                            <strong>ISBN:</strong>
                                                                                                        </p>
                                                                                                        <p>{{ $reservation->ouvrage->isbn ?? 'Non renseigné' }}
                                                                                                        </p>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-md-6">
                                                                                                        <p
                                                                                                            class="mb-1">
                                                                                                            <strong>Année:</strong>
                                                                                                        </p>
                                                                                                        <p>{{ $reservation->ouvrage->annee_publication ?? 'Non renseigné' }}
                                                                                                        </p>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Notes supplémentaires -->
                                                                        @if ($reservation->notes)
                                                                            <div class="row mt-4">
                                                                                <div class="col-md-12">
                                                                                    <div class="card">
                                                                                        <div
                                                                                            class="card-header bg-light">
                                                                                            <h6 class="mb-0"><i
                                                                                                    class="fas fa-sticky-note mr-2"></i>Notes
                                                                                                supplémentaires</h6>
                                                                                        </div>
                                                                                        <div class="card-body">
                                                                                            <p class="mb-0">
                                                                                                {{ $reservation->notes }}
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                            <i class="fas fa-times mr-1"></i> Fermer
                                                                        </button>
                                                                        @if ($reservation->statut == 'en_attente')
                                                                            <button type="button"
                                                                                class="btn btn-success"
                                                                                data-dismiss="modal"
                                                                                data-toggle="modal"
                                                                                data-target="#validateReservationModal-{{ $reservation->id }}">
                                                                                <i class="fas fa-check mr-1"></i>
                                                                                Valider
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Bouton Modifier -->
                                                        @if ($reservation->statut == 'en_attente')
                                                            <!-- Modal de validation -->
                                                            <div class="modal fade"
                                                                id="validateReservationModal-{{ $reservation->id }}"
                                                                tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div
                                                                            class="modal-header bg-success text-white">
                                                                            <h5 class="modal-title">Valider la
                                                                                réservation
                                                                            </h5>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
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
                                                                                method="POST"
                                                                                style="display: inline;">
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
                                                            <button type="button"
                                                                class="btn btn-outline-success mx-1" title="Valider"
                                                                data-toggle="modal"
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
                                                                            <h5 class="modal-title">Annuler la
                                                                                réservation
                                                                            </h5>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
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
                                                                                method="POST"
                                                                                style="display: inline;">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">
                                                                                    <i class="fas fa-times mr-1"></i>
                                                                                    Oui,
                                                                                    annuler
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-outline-danger mx-1"
                                                                title="Annuler" data-toggle="modal"
                                                                data-target="#cancelReservationModal-{{ $reservation->id }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
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
    </div>


    <!-- Scripts spécifiques aux réservations -->
    <!-- Scripts -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.datepicker').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                locale: "fr"
            });

            // Initialisation DataTables sans pagination
            const table = $('#reservations-table').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                }
            });

            // Filtre personnalisé
            $('[data-filter]').on('click', function() {
                const filter = $(this).data('filter');

                if (filter === 'all') {
                    table.$('tr').show();
                } else {
                    table.$('tr').hide();
                    table.$('tr[data-status="' + filter + '"]').show(); // Affiche les lignes du bon statut
                }
            });
        });
    </script>

</body>

</html>
