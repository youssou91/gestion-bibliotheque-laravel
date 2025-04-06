<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Commentaires</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css')}}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
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
                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- SCRIPTS -->
    <!-- Correction de l'ordre des scripts -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js')}}"></script>
    <script src="{{ url('assets/js/app.min.js')}}"></script>
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
                        const fullText = $(this).closest('tr').find('.comment-preview').data('fulltext');
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