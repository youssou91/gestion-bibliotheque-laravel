<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ouvrages - Bibliothèque</title>
    <!-- CSS -->
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

        .badge-disponible {
            background-color: #28a745;
        }

        .badge-reserve {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-emprunte {
            background-color: #dc3545;
        }

        .book-cover {
            width: 40px;
            height: 55px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #eee;
        }

        .book-cover-lg {
            width: 160px;
            height: 220px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .niveau-badge {
            min-width: 100px;
            display: inline-block;
            text-align: center;
        }

        .niveau-debutant { background-color: #17a2b8; }
        .niveau-amateur { background-color: #28a745; }
        .niveau-chef { background-color: #dc3545; }
        .niveau-intermediaire { background-color: #ffc107; color: #212529; }

        .action-buttons .btn {
            margin: 0 2px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .isbn-display {
            font-family: monospace;
            letter-spacing: 1px;
        }

        .table-responsive {
            padding: 0 15px;
        }

        .card-header h1 {
            font-size: 1.8rem;
            padding: 15px 0;
        }

        .book-preview {
            cursor: pointer;
            transition: color 0.2s;
            font-weight: 400;
        }

        .book-preview:hover {
            color: #0d6efd;
            text-decoration: underline;
        }

        .availability-badge {
            min-width: 100px;
            display: inline-block;
            text-align: center;
        }

        .avatar {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <!-- En-tête -->
                    <div class="card-header bg-primary text-white mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="mt-2"><i class="fas fa-book-open mr-2"></i>Gestion des Ouvrages</h1>
                            <a href="{{ route('routeAjoutCat.getCategories') }}" class="btn btn-light">
                                <i class="fas fa-plus mr-1"></i> Ajouter un Ouvrage
                            </a>
                        </div>
                    </div>
                    <!-- Message de succès -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Tableau des ouvrages -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des ouvrages</h5>
                            
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="ouvragesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Couverture</th>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Éditeur</th>
                                            <th>ISBN</th>
                                            <th>Prix</th>
                                            <th>Niveau</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($livres as $livre)
                                            <tr data-niveau="{{ $livre->niveau }}">
                                                <td>#{{ str_pad($livre->id, 4, '0', STR_PAD_LEFT) }}</td>
                                                <td>
                                                    <img src="{{ $livre->photo ? asset('assets/img/' . $livre->photo) : asset('assets/img/no-cover.jpg') }}" 
                                                         alt="Couverture" class="book-cover">
                                                </td>
                                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <a href="#" class="book-preview" data-toggle="modal"
                                                       data-target="#viewOuvrageModal-{{ $livre->id }}">
                                                        {{ $livre->titre }}
                                                    </a>
                                                </td>
                                                <td>{{ $livre->auteur }}</td>
                                                <td>{{ $livre->editeur }}</td>
                                                <td class="isbn-display">{{ $livre->isbn }}</td>
                                                <td>{{ number_format($livre->prix, 2) }} $</td>
                                                <td>
                                                    @php
                                                        $niveauClass = [
                                                            'debutant' => 'niveau-debutant',
                                                            'amateur' => 'niveau-amateur',
                                                            'chef' => 'niveau-chef',
                                                            'intermédiaire' => 'niveau-intermediaire'
                                                        ][$livre->niveau] ?? 'badge-secondary';
                                                    @endphp
                                                    <span class="badge niveau-badge {{ $niveauClass }}">
                                                        {{ ucfirst($livre->niveau) }}
                                                    </span>
                                                </td>
                                               
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- Bouton Voir détails -->
                                                        <button type="button" class="btn btn-outline-info mx-1"
                                                            title="Voir détails" data-toggle="modal"
                                                            data-target="#viewOuvrageModal-{{ $livre->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <!-- Bouton Modifier -->
                                                        <a href="{{ route('routeModifierOuvrage.edit', $livre->id) }}"
                                                           class="btn btn-outline-primary mx-1" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <!-- Bouton Supprimer -->
                                                        <button type="button" class="btn btn-outline-danger mx-1"
                                                            title="Supprimer" data-toggle="modal"
                                                            data-target="#deleteOuvrageModal-{{ $livre->id }}">
                                                            <i class="fas fa-trash"></i>
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
                                Affichage de <b>{{ $livres->firstItem() }}</b> à
                                <b>{{ $livres->lastItem() }}</b> sur <b>{{ $livres->total() }}</b> ouvrages
                            </div>
                            <div>
                                {{ $livres->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals pour chaque ouvrage -->
    @foreach ($livres as $livre)
    <!-- Modal Détails Ouvrage -->
    <div class="modal fade" id="viewOuvrageModal-{{ $livre->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-book-open mr-2"></i>
                        {{ $livre->titre }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Section Détails Ouvrage -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Détails de l'Ouvrage</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-2">
                                        @if ($livre->photo)
                                            <img src="{{ asset('assets/img/' . $livre->photo) }}" 
                                                 class="book-cover-lg mb-2" alt="Couverture">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="height: 220px; width: 160px; margin: 0 auto;">
                                                <i class="fas fa-book-open fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-user mr-2 text-primary"></i>Auteur</span>
                                            <span>{{ $livre->auteur }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-building mr-2 text-secondary"></i>Éditeur</span>
                                            <span>{{ $livre->editeur }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-barcode mr-2 text-dark"></i>ISBN</span>
                                            <span class="isbn-display">{{ $livre->isbn }}</span>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Section  et Catégorie -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>  Classification</h6>
                                </div>
                                <div class="card-body">
                                    {{--  --}}

                                    <div class="mb-4">
                                        <h6 class="mb-3"><i class="fas fa-layer-group mr-2"></i>Niveau</h6>
                                        @php
                                            $niveauClass = [
                                                'debutant' => 'niveau-debutant',
                                                'amateur' => 'niveau-amateur',
                                                'chef' => 'niveau-chef',
                                                'intermédiaire' => 'niveau-intermediaire'
                                            ][$livre->niveau] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge niveau-badge {{ $niveauClass }}" style="font-size: 1rem;">
                                            {{ ucfirst($livre->niveau) }}
                                        </span>
                                    </div>

                                    <div>
                                        <h6 class="mb-3"><i class="fas fa-list mr-2"></i>Catégorie</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-info" style="font-size: 1rem;">
                                                {{ $livre->categorie->nom ?? 'Non classé' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar mr-2 text-info"></i>Année</span>
                                                <span>{{ $livre->annee_publication }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-tag mr-2 text-warning"></i>Prix</span>
                                                <span>{{ number_format($livre->prix, 2) }} $</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Description -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-align-left mr-2"></i>Description</h6>
                                </div>
                                <div class="card-body">
                                    @if($livre->description)
                                        <p>{{ $livre->description }}</p>
                                    @else
                                        <p class="text-muted">Aucune description disponible pour cet ouvrage.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Fermer
                    </button>
                    <a href="{{ route('routeModifierOuvrage.edit', $livre->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-1"></i> Modifier
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal Confirmation Suppression -->
    <div class="modal fade" id="deleteOuvrageModal-{{ $livre->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Confirmer la suppression</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'ouvrage "<strong>{{ $livre->titre }}</strong>" ?</p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Cette action est irréversible et supprimera toutes les données associées à cet ouvrage.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </button>
                    <form action="{{ route('routeSupprimerOuvrage.destroy', $livre->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Confirmer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

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
            // Initialisation DataTables
            const table = $('#ouvragesTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [
                    { orderable: false, targets: [9] } // Désactiver le tri sur la colonne Actions
                ]
            });

            

            
        });
    </script>
</body>
</html>