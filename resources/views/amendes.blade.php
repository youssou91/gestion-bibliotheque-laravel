<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Amendes</title>
    <!-- CSS -->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card-header bg-primary text-white">
                        <h1 class="mt-4"><i class="fas fa-money-bill-wave mr-2"></i>Gestion des Amendes</h1>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mt-4 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total'] }}</h3>
                                    <p>Total des Amendes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['impayees'] }}</h3>
                                    <p>Amendes Impayées</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['payees'] }}</h3>
                                    <p>Amendes Payées</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ number_format($stats['montant_total'], 2) }} $</h3>
                                    <p>Montant Total</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des amendes -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="amendesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#ID</th>
                                            <th>Utilisateur</th>
                                            <th>Ouvrage</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Date </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($amendes as $amende)
                                            <tr>
                                                <td>#{{ str_pad($amende->id, 4, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $amende->utilisateur->prenom }} {{ $amende->utilisateur->nom }}
                                                </td>
                                                <td>{{ $amende->ouvrage->titre }}</td>
                                                <td>{{ number_format($amende->montant, 2) }} $</td>
                                                <td>
                                                    @if ($amende->est_payee)
                                                        <span class="badge badge-success">Payée</span>
                                                    @else
                                                        <span class="badge badge-danger">Impayée</span>
                                                    @endif
                                                </td>
                                                <td>{{ $amende->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center" style="width: 150px">
                                                    {{-- <a href="{{ url('amendes.show', $amende->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Voir détails">
                                                       <i class="fas fa-eye"></i>
                                                    </a> --}}
                                                    <a href="#" class="btn btn-sm btn-outline-info"
                                                        title="Voir détails" data-toggle="modal"
                                                        data-target="#viewAmendeModal-{{ $amende->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if (!$amende->est_payee)
                                                        <form method="POST"
                                                            action="{{ url('amendes.payer', $amende->id) }}"
                                                            style="display:inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-success"
                                                                title="Marquer comme payée"
                                                                onclick="return confirm('Confirmer le paiement de cette amende?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form method="POST"
                                                        action="{{ url('amendes.destroy', $amende->id) }}"
                                                        style="display:inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Supprimer"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette amende?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <!-- Modal pour voir les détails d'une amende -->
                                                <div class="modal fade" id="viewAmendeModal-{{ $amende->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="viewAmendeModalLabel-{{ $amende->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title"
                                                                    id="viewAmendeModalLabel-{{ $amende->id }}">
                                                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                                                    Amende
                                                                    #{{ str_pad($amende->id, 5, '0', STR_PAD_LEFT) }}
                                                                </h5>
                                                                <button type="button" class="close text-white"
                                                                    data-dismiss="modal" aria-label="Fermer">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">

                                                                    <!-- Informations Utilisateur -->
                                                                    <div class="col-md-6 mb-4">
                                                                        <div class="card">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0"><i
                                                                                        class="fas fa-user mr-2"></i>Informations
                                                                                    Utilisateur</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <p><strong>Nom :</strong>
                                                                                    {{ $amende->utilisateur->prenom }}
                                                                                    {{ $amende->utilisateur->nom }}</p>
                                                                                <p><strong>Email :</strong>
                                                                                    {{ $amende->utilisateur->email }}
                                                                                </p>
                                                                                <p><strong>Téléphone :</strong>
                                                                                    {{ $amende->utilisateur->telephone ?? 'Non renseigné' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Informations Amende -->
                                                                    <div class="col-md-6 mb-4">
                                                                        <div class="card">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0"><i
                                                                                        class="fas fa-file-invoice-dollar mr-2"></i>Détails
                                                                                    de l'Amende</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <p><strong>Montant :</strong>
                                                                                    {{ number_format($amende->montant, 2) }}
                                                                                    $</p>
                                                                                <p><strong>Statut :</strong>
                                                                                    <span
                                                                                        class="badge badge-{{ $amende->est_payee ? 'success' : 'danger' }}">
                                                                                        {{ $amende->est_payee ? 'Payée' : 'Impayée' }}
                                                                                    </span>
                                                                                </p>
                                                                                <p><strong>Date d’émission :</strong>
                                                                                    {{ $amende->created_at->format('d/m/Y') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Informations sur l'ouvrage -->
                                                                    <div class="col-md-12">
                                                                        <div class="card">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0"><i
                                                                                        class="fas fa-book mr-2"></i>Ouvrage
                                                                                    concerné</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <h5>{{ $amende->ouvrage->titre }}</h5>
                                                                                <p class="text-muted">
                                                                                    {{ $amende->ouvrage->auteur }}</p>
                                                                                <p><strong>ISBN :</strong>
                                                                                    {{ $amende->ouvrage->isbn ?? 'Non renseigné' }}
                                                                                </p>
                                                                                <p><strong>Année :</strong>
                                                                                    {{ $amende->ouvrage->annee_publication ?? 'Non renseignée' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <i class="fas fa-times mr-1"></i> Fermer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#amendesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                order: [
                    [5, 'desc']
                ],
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    },
                    {
                        orderable: false,
                        targets: -1
                    }
                ]
            });

            // Afficher les messages flash
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif
            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
</body>

</html>
