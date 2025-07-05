<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Commentaires</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .comment-preview {
            cursor: pointer;
            transition: color 0.2s;
        }

        .comment-preview:hover {
            color: #0d6efd;
        }

        .star-rating {
            display: inline-block;
            white-space: nowrap;
        }
    </style>
</head>

<body class="fixed-navbar">
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mt-4"><i class="fas fa-comments mr-2"></i>Validation des Commentaires</h1>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-warning">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-warning text-white mb-2">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $pendingCount }}</h3>
                                        <p class="text-muted mb-0">En Attente</p>
                                        <div class="text-xs text-warning mt-1">
                                            <i class="fas fa-exclamation-circle"></i> À modérer
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-success">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-success text-white mb-2">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $approuve30 }}</h3>
                                        <p class="text-muted mb-0">Approuvés (30j)</p>
                                        <div class="text-xs text-success mt-1">
                                            <i class="fas fa-database"></i> Total: {{ $approuve }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-danger">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-danger text-white mb-2">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $rejete30 }}</h3>
                                        <p class="text-muted mb-0">Rejetés (30j)</p>
                                        <div class="text-xs text-danger mt-1">
                                            <i class="fas fa-database"></i> Total: {{ $rejete }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card stat-card border-0 bg-light-primary">
                                    <div class="card-body text-center p-3">
                                        <div class="stat-icon bg-primary text-white mb-2">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <h3 class="mb-1">{{ $pendingCount + $approuve + $rejete }}</h3>
                                        <p class="text-muted mb-0">Total Commentaires</p>
                                        <div class="text-xs text-primary mt-1">
                                            <i class="fas fa-eye"></i> {{ $comments->count() }} affichés
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" id="comments-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 25%">Commentaire</th>
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
                                                    <td>
                                                        <div class="comment-preview" data-content="{{ $comment->contenu }}">
                                                            {{ Str::words($comment->contenu, 7, '...') }}
                                                        </div>
                                                    </td>
                                                    <td>{{ optional($comment->ouvrage)->titre ?? '—' }}</td>
                                                    <td>{{ optional($comment->utilisateur)->prenom ?? '—' }} {{ optional($comment->utilisateur)->nom ?? '—' }}</td>
                                                    <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                                    <td class="text-center">
                                                        <div class="star-rating">
                                                            @for ($i = 1; $i <= floor($comment->note); $i++)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @endfor
                                                            @if ($comment->note - floor($comment->note) >= 0.5)
                                                                <i class="fas fa-star-half-alt text-warning"></i>
                                                                @php $i++ @endphp
                                                            @endif
                                                            @for ($j = $i; $j <= 5; $j++)
                                                                <i class="far fa-star text-warning"></i>
                                                            @endfor
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusClass = [
                                                                'en_attente' => 'warning',
                                                                'approuve' => 'success',
                                                                'rejete' => 'danger',
                                                            ][$comment->statut];
                                                            
                                                            $statusIcon = [
                                                                'en_attente' => 'fa-hourglass-half',
                                                                'approuve' => 'fa-check-circle',
                                                                'rejete' => 'fa-times-circle',
                                                            ][$comment->statut];
                                                        @endphp
                                                        <span class="badge badge-{{ $statusClass }}">
                                                            <i class="fas {{ $statusIcon }} mr-1"></i>
                                                            {{ ucfirst(str_replace('_', ' ', $comment->statut)) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            @if ($comment->statut === 'en_attente')
                                                                <button type="button" class="btn btn-outline-success mx-1" 
                                                                    title="Approuver" data-toggle="modal"
                                                                    data-target="#approveCommentModal-{{ $comment->id }}">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                
                                                                <div class="modal fade" id="approveCommentModal-{{ $comment->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-success text-white">
                                                                                <h5 class="modal-title">Approuver le commentaire</h5>
                                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Confirmez-vous l'approbation de ce commentaire ?</p>
                                                                                <div class="alert alert-info mt-3">
                                                                                    <i class="fas fa-info-circle mr-2"></i>
                                                                                    Le commentaire sera visible par tous les utilisateurs.
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                                <form action="{{ route('admin.approuve', $comment->id) }}" method="POST" style="display: inline;">
                                                                                    @csrf
                                                                                    <button type="submit" class="btn btn-success">
                                                                                        <i class="fas fa-check mr-1"></i> Confirmer
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <button type="button" class="btn btn-outline-danger mx-1" 
                                                                    title="Rejeter" data-toggle="modal"
                                                                    data-target="#rejectCommentModal-{{ $comment->id }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                                
                                                                <div class="modal fade" id="rejectCommentModal-{{ $comment->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-danger text-white">
                                                                                <h5 class="modal-title">Rejeter le commentaire</h5>
                                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Confirmez-vous le rejet de ce commentaire ?</p>
                                                                                <div class="alert alert-warning mt-3">
                                                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                                    Le commentaire ne sera pas visible par les utilisateurs.
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                                <form action="{{ route('admin.reject', $comment->id) }}" method="POST" style="display: inline;">
                                                                                    @csrf
                                                                                    <button type="submit" class="btn btn-danger">
                                                                                        <i class="fas fa-times mr-1"></i> Confirmer
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            
                                                            <button type="button" class="btn btn-outline-info mx-1" 
                                                                title="Voir détails" data-toggle="modal"
                                                                data-target="#viewCommentModal-{{ $comment->id }}">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            
                                                            <div class="modal fade" id="viewCommentModal-{{ $comment->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary text-white">
                                                                            <h5 class="modal-title">
                                                                                <i class="fas fa-comment-dots mr-2"></i> Détails du commentaire
                                                                            </h5>
                                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row mb-4">
                                                                                <div class="col-md-6">
                                                                                    <div class="card h-100">
                                                                                        <div class="card-header bg-light">
                                                                                            <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Informations Auteur</h6>
                                                                                        </div>
                                                                                        <div class="card-body">
                                                                                            <div class="d-flex align-items-center mb-3">
                                                                                                <div class="avatar bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center"
                                                                                                    style="width: 50px; height: 50px; font-size: 1.2rem;">
                                                                                                    {{ substr(optional($comment->utilisateur)->prenom ?? 'A', 0, 1) }}{{ substr(optional($comment->utilisateur)->nom ?? 'U', 0, 1) }}
                                                                                                </div>
                                                                                                <div>
                                                                                                    <h5 class="mb-1">
                                                                                                        {{ optional($comment->utilisateur)->prenom ?? 'Anonyme' }} 
                                                                                                        {{ optional($comment->utilisateur)->nom ?? '' }}
                                                                                                    </h5>
                                                                                                    <p class="text-muted mb-1">
                                                                                                        {{ optional($comment->utilisateur)->email ?? 'Non renseigné' }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="card h-100">
                                                                                        <div class="card-header bg-light">
                                                                                            <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Informations Livre</h6>
                                                                                        </div>
                                                                                        <div class="card-body">
                                                                                            <h5>{{ optional($comment->ouvrage)->titre ?? 'Livre supprimé' }}</h5>
                                                                                            <p class="text-muted">{{ optional($comment->ouvrage)->auteur ?? 'Auteur inconnu' }}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="card mb-4">
                                                                                <div class="card-header bg-light">
                                                                                    <h6 class="mb-0"><i class="fas fa-comment mr-2"></i>Commentaire</h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="d-flex justify-content-between mb-3">
                                                                                        <div>
                                                                                            <span class="badge badge-{{ $statusClass }}">
                                                                                                <i class="fas {{ $statusIcon }} mr-1"></i>
                                                                                                {{ ucfirst(str_replace('_', ' ', $comment->statut)) }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="text-muted">
                                                                                            <small>Posté le {{ $comment->created_at->format('d/m/Y à H:i') }}</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="star-rating mb-3">
                                                                                        @for ($i = 1; $i <= floor($comment->note); $i++)
                                                                                            <i class="fas fa-star fa-lg text-warning"></i>
                                                                                        @endfor
                                                                                        @if ($comment->note - floor($comment->note) >= 0.5)
                                                                                            <i class="fas fa-star-half-alt fa-lg text-warning"></i>
                                                                                            @php $i++ @endphp
                                                                                        @endif
                                                                                        @for ($j = $i; $j <= 5; $j++)
                                                                                            <i class="far fa-star fa-lg text-warning"></i>
                                                                                        @endfor
                                                                                        <span class="ml-2">({{ $comment->note }}/5)</span>
                                                                                    </div>
                                                                                    
                                                                                    <div class="bg-light p-3 rounded">
                                                                                        <i class="fas fa-quote-left text-muted float-left mr-2"></i>
                                                                                        <p class="mb-0">{{ $comment->contenu }}</p>
                                                                                        <i class="fas fa-quote-right text-muted float-right ml-2"></i>
                                                                                        <div class="clearfix"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                                <i class="fas fa-times mr-1"></i> Fermer
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
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
    </div>

    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#comments-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                order: [[3, 'desc']],
                columnDefs: [{
                    targets: 6,
                    orderable: false,
                    searchable: false
                }]
            });

            $('.comment-preview').click(function() {
                const content = $(this).data('content');
                const row = $(this).closest('tr');
                const book = row.find('td:eq(1)').text();
                const author = row.find('td:eq(2)').text();
                const date = row.find('td:eq(3)').text();
                const rating = row.find('.star-rating').html();

                const modalId = row.find('.btn-outline-info').data('target');
                $(modalId).modal('show');
            });
        });
    </script>
</body>
</html> 