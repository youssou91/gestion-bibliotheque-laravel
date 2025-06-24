@extends('frontOffice.layouts.app')
@section('content')

    <div id="home" class="container py-4">
        <!-- Titre -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white rounded shadow-sm p-4">
                    <h2 class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-warning"></i>
                        Mes réservations
                    </h2>
                </div>
            </div>
        </div>

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

        <!-- Onglets -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="reservationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                            type="button" role="tab">
                            <i class="fas fa-clock me-2"></i>Réservations actives
                            <span class="badge bg-warning ms-2">{{ $reservationsActives->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                            type="button" role="tab">
                            <i class="fas fa-times-circle me-2"></i>Historique
                            <span class="badge bg-secondary ms-2">{{ $reservationsHistorique->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content bg-white p-4 rounded-bottom shadow-sm">
                    <!-- Réservations actives -->
                    <div class="tab-pane fade show active" id="active" role="tabpanel">
                        @if ($reservationsActives->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune réservation active pour le moment.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($reservationsActives as $reservation)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        {{-- <div class="card h-100 border-0 shadow-sm">
                                        <img src="{{ asset('assets/img/' . $reservation->ouvrage->photo) }}"
                                            class="card-img-top" alt="{{ $reservation->ouvrage->titre }}"
                                            style="height: 250px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $reservation->ouvrage->titre }}</h5>
                                            <p class="card-text text-muted">
                                                <small>Auteur: {{ $reservation->ouvrage->auteur }}</small><br>
                                                <small>Réservé le: {{ $reservation->date_reservation->format('d/m/Y') }}</small>
                                            </p>
                                            <span class="badge bg-{{ $reservation->statut === 'validee' ? 'success' : 'warning' }}">
                                                {{ ucfirst($reservation->statut) }}
                                            </span>
                                            @if ($reservation->statut === 'en_attente')
                                                <div class="mt-3">
                                                    <a href="{{ url('frontOffice.reservations.cancel', $reservation->id) }}"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fas fa-times me-1"></i>Annuler
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div> --}}
                                        <div class="card h-100 border-0 shadow-sm">
                                            <img src="{{ asset('assets/img/' . $reservation->ouvrage->photo) }}"
                                                class="card-img-top" alt="{{ $reservation->ouvrage->titre }}"
                                                style="height: 250px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $reservation->ouvrage->titre }}</h5>
                                                <p class="card-text text-muted mb-2">
                                                    <small>Auteur : {{ $reservation->ouvrage->auteur }}</small><br>
                                                    <small>Réservé le :
                                                        {{ \Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y') }}</small>
                                                </p>

                                                <span
                                                    class="badge bg-{{ $reservation->statut === 'validee' ? 'success' : ($reservation->statut === 'annulee' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($reservation->statut) }}
                                                </span>

                                                @if (in_array($reservation->statut, ['en_attente', 'validee']))
                                                    <div class="mt-3">
                                                        {{-- <form
                                                            action="{{ url('frontOffice.reservations.annuler', $reservation->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Confirmer l’annulation de cette réservation ?');">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-danger w-100">
                                                                <i class="fas fa-times me-1"></i> Annuler la réservation
                                                            </button>
                                                        </form> --}}
                                                        <button type="button"
                                                            class="btn btn-sm d-flex align-items-center justify-content-center w-100 mt-3 px-3 py-2"
                                                            style="background: linear-gradient(135deg, #ff6a6a, #ff4d4d); color: white; border: none; border-radius: 8px;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalAnnulerReservation{{ $reservation->id }}">
                                                            <i class="fas fa-calendar-times me-2 fs-5"></i> Annuler la
                                                            réservation
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <!-- Modal pour annuler une réservation -->
                    <!-- Modal Bootstrap -->
                    <div class="modal fade" id="modalAnnulerReservation
                    {{-- {{ $reservation->id }} --}}
                     " tabindex="-1"
                        aria-labelledby="modalLabel
                        {{-- {{ $reservation->id }} --}}
                         " aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow-sm border-0 rounded-3">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="modalLabel
                                    {{-- {{ $reservation->id }} --}}
                                     ">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Confirmation
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Voulez-vous vraiment <strong>annuler</strong> votre réservation pour :</p>
                                    <p class="mb-0 text-center">

                                        {{-- <strong>{{ $reservation->ouvrage->titre }}</strong><br> --}}
                                        {{-- <small class="text-muted">{{ $reservation->ouvrage->auteur }}</small> --}}
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non,
                                        garder</button>

                                    <form action="{{ url('frontOffice.reservations.annuler'
                                    // $reservation->id
                                    ) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-check me-1"></i> Oui, annuler
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        @if ($reservationsHistorique->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune réservation dans l’historique.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Livre</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reservationsHistorique as $reservation)
                                            <tr>
                                                <td>
                                                    <strong>{{ $reservation->ouvrage->titre }}</strong><br>
                                                    <small>{{ $reservation->ouvrage->auteur }}</small>
                                                </td>
                                                <td>{{ $reservation->date_reservation->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-danger">{{ ucfirst($reservation->statut) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $reservationsHistorique->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
