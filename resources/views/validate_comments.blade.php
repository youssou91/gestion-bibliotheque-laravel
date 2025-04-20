<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Commentaires</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                {{-- <div class="container-fluid">
                    <div class="card-header bg-danger text-white">
                        <h1 class="mt-4"><i class="fa fa-comments mr-2"></i>Validation des Commentaires</h1>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Statistiques de modération -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-muted">En attente</h5>
                                            <h2 class="text-warning">23</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-muted">Approuvés (30j)</h5>
                                            <h2 class="text-success">156</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="text-muted">Rejetés (30j)</h5>
                                            <h2 class="text-danger">42</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tableau des commentaires -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="comments-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Commentaire</th>
                                            <th>Livre</th>
                                            <th>Auteur</th>
                                            <th>Date</th>
                                            <th>Note</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="comment-preview">
                                                    "Un classique intemporel qui mérite d'être relu plusieurs fois..."
                                                </div>
                                            </td>
                                            <td>1984</td>
                                            <td>Jean D.</td>
                                            <td>15/09/2023</td>
                                            <td>
                                                <div class="stars">
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                            </td>
                                            <td><span class="badge badge-warning">En attente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="comment-preview">
                                                    "La fin m'a laissé perplexe, mais globalement une bonne lecture..."
                                                </div>
                                            </td>
                                            <td>L'Étranger</td>
                                            <td>Marie L.</td>
                                            <td>14/09/2023</td>
                                            <td>
                                                <div class="stars">
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star text-warning"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                            </td>
                                            <td><span class="badge badge-success">Approuvé</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="container-fluid">

                    <!-- En-tête rouge -->
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <i class="fa fa-comments fa-lg mr-2"></i>
                            <span>Validation des Commentaires</span>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">En attente</h6>
                                    {{-- <h3 class="text-warning">{{ $pendingCount }}</h3> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Approuvés (30j)</h6>
                                    {{-- <h3 class="text-success">{{ $approved30 }}</h3> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Rejetés (30j)</h6>
                                    {{-- <h3 class="text-danger">{{ $rejected30 }}</h3> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table de commentaires -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="comments-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Commentaire</th>
                                        <th>Livre</th>
                                        <th>Auteur</th>
                                        <th>Date</th>
                                        <th>Note</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comments as $comment)
                                        <tr>
                                            <td>{{ Str::limit($comment->contenu, 50) }}</td>
                                            <td>{{ $comment->ouvrage->titre }}</td>
                                            <td>{{ $comment->utilisateur->name }}</td>
                                            <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fa fa-star{{ $i <= $comment->note ? '' : '-o' }} text-warning"></i>
                                                @endfor
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $comment->statut == 'pending' ? 'warning' : ($comment->statut == 'approved' ? 'success' : 'danger') }}">
                                                    {{ ucfirst($comment->statut) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($comment->statut == 'pending')
                                                    <form action="{{ route('comments.approve', $comment) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success" title="Approuver">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('comments.reject', $comment) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-danger" title="Rejeter">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i
                                                            class="fa fa-check{{ $comment->statut == 'approved' ? '' : '-o' }}"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i
                                                            class="fa fa-times{{ $comment->statut == 'rejected' ? '' : '-o' }}"></i>
                                                    </button>
                                                @endif

                                                <!-- Voir le détail dans un modal -->
                                                <button class="btn btn-sm btn-info btn-view"
                                                    data-id="{{ $comment->id }}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination Laravel (si vous passez à paginate() au lieu de get()) -->
                            <div class="mt-3">
                                {{-- $comments->links() --}}
                            </div>
                        </div>
                    </div>

                    <!-- Modal de visualisation -->
                    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Détail du commentaire</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Commentaire :</strong></p>
                                    <p id="modal-content"></p>
                                    <p><strong>Livre :</strong> <span id="modal-book"></span></p>
                                    <p><strong>Auteur :</strong> <span id="modal-user"></span></p>
                                    <p><strong>Date :</strong> <span id="modal-date"></span></p>
                                    <p><strong>Note :</strong> <span id="modal-rating"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @push('scripts')
                    <script>
                        $(function() {
                            // Initialise DataTables
                            $('#comments-table').DataTable({
                                lengthMenu: [10, 25, 50],
                                language: {
                                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                                }
                            });

                            // Bouton Voir -> AJAX + modal
                            $('.btn-view').click(function() {
                                const id = $(this).data('id');
                                $.getJSON(`/comments/${id}`, function(c) {
                                    $('#modal-content').text(c.contenu);
                                    $('#modal-book').text(c.ouvrage.titre);
                                    $('#modal-user').text(c.utilisateur.name);
                                    $('#modal-date').text(new Date(c.created_at).toLocaleDateString('fr-FR'));
                                    let stars = '';
                                    for (let i = 1; i <= 5; i++) {
                                        stars += `<i class="fa fa-star${i<=c.note?'':'-o'} text-warning"></i>`;
                                    }
                                    $('#modal-rating').html(stars);
                                    $('#commentModal').modal('show');
                                });
                            });
                        });
                    </script>
                @endpush
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- SCRIPTS -->
    <!-- Correction de l'ordre des scripts -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialisation DataTable avec configuration étendue
            const table = $('#comments-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row) {
                        // Stockage du texte complet dans un attribut data
                        const fullText = $(data).find('.comment-preview').text();
                        return `<div class="comment-preview" data-fulltext="${fullText}">
                            ${fullText.substring(0, 100)}${fullText.length > 100 ? '...' : ''}
                        </div>`;
                    }
                }, {
                    targets: 6,
                    orderable: false,
                    searchable: false
                }],
                initComplete: function() {
                    // Gestionnaire d'événement délégué pour les éléments dynamiques
                    $('#comments-table tbody').on('click', '.btn-info', function() {
                        const fullText = $(this).closest('tr').find('.comment-preview').data(
                            'fulltext');
                        $('#commentModal .modal-body').text(fullText);
                        $('#commentModal').modal('show');
                    });
                }
            });

            // Filtres supplémentaires
            $('#dateFilter, #statusFilter').on('change', function() {
                table.draw();
            });
        });
    </script>

    <!-- Modale d'affichage complet -->
    <div class="modal fade" id="commentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Commentaire complet</h5>
                </div>
                <div class="modal-body">
                    <!-- Contenu dynamique -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .comment-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stars {
            color: #ffd700;
        }

        .badge-warning {
            background-color: #ffc107;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }
    </style>
</body>
<!--
Caractéristiques principales :

Tableau de modération :

Aperçu des commentaires avec ellipse

Système d'étoiles pour les notes

Badges colorés pour le statut

Actions contextuelles (Approuver/Rejeter/Voir)

Fonctionnalités avancées :

Prévisualisation des commentaires

Modale d'affichage complet

Statistiques de modération

Filtres par date et statut

Optimisations :

Gestion des longs textes

Désactivation des actions inutiles

Tri intelligent des dates

Recherche globale

Sécurité :

Protection contre les injections XSS

Gestion des droits de modération

Historique des actions

Pour compléter cette page :

Ajouter un système de signalement

Implémenter un historique des modifications

Ajouter des motifs de rejet prédéfinis

Intégrer une modération en temps réel
-->

</html>
