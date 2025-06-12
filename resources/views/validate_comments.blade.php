<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Commentaires</title>

    <!-- STYLES -->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <!-- En-tête -->
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <i class="fa fa-comments fa-lg mr-2"></i>
                            <span>Validation des Commentaires</span>
                        </div>
                    </div>
                    <!-- Statistiques améliorées -->
                    <div class="row mb-4">
                        <!-- Carte Commentaires en attente -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                <i class="fas fa-clock mr-1"></i> En attente
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}
                                            </div>
                                            <div class="mt-2">
                                                <span class="badge badge-warning badge-pill">
                                                    <i class="fas fa-exclamation mr-1"></i> À modérer
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Approuvés (30 jours) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                <i class="fas fa-check-circle mr-1"></i> Approuvés (30j)
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approuve30 }}
                                            </div>
                                            <div class="mt-2 text-muted">
                                                <small>
                                                    <i class="fas fa-database mr-1"></i> Total: {{ $approuve }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-thumbs-up fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Rejetés (30 jours) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                <i class="fas fa-ban mr-1"></i> Rejetés (30j)
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rejete30 }}
                                            </div>
                                            <div class="mt-2 text-muted">
                                                <small>
                                                    <i class="fas fa-database mr-1"></i> Total: {{ $rejete }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-thumbs-down fa-2x text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Tous les commentaires -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <i class="fas fa-comments mr-1"></i> Total 
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $pendingCount + $approuve + $rejete }}
                                            </div>
                                            <div class="mt-2">
                                                <span class="badge badge-info badge-pill">
                                                    <i class="fas fa-eye mr-1"></i> {{ $comments->count() }} affichés
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-pie fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tableau -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="comments-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Commentaire</th> <!-- Colonne élargie -->
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
                                            <td style="width: 25%">
                                                <div class="comment-preview" data-content="{{ $comment->contenu }}">
                                                    {{ Str::words($comment->contenu, 7, '...') }}
                                                </div>
                                            </td>
                                            <td>{{ optional($comment->ouvrage)->titre ?? '—' }}</td>
                                            <td>{{ optional($comment->utilisateur)->nom ?? '—' }}</td>
                                            <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                            <td style="text-align: center; width: 120px">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas {{ $i <= $comment->note ? 'fa-star' : 'fa-star-o' }} text-warning"></i>
                                                @endfor
                                            </td>
                                            <td>
                                                @php
                                                    $statut = $comment->statut;
                                                    if ($statut === 'en_attente') {
                                                        $class = 'warning';
                                                        $icon = 'fa-hourglass-half';
                                                    } elseif ($statut === 'approuve') {
                                                        $class = 'success';
                                                        $icon = 'fa-check-circle';
                                                    } elseif ($statut === 'rejete') {
                                                        $class = 'danger';
                                                        $icon = 'fa-times-circle';
                                                    } else {
                                                        $class = 'secondary';
                                                        $icon = 'fa-question-circle';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $class }}">
                                                    <i class="fas {{ $icon }} mr-1"></i>
                                                </span>
                                            </td>
                                            <td class="text-center" style="width: 100px;">
                                                <div class="btn-group d-flex justify-content-center"
                                                    style="gap: 5px;">
                                                    @if ($comment->statut === 'en_attente')
                                                        <button type="button"
                                                            class="btn btn-sm btn-success approve-comment d-flex align-items-center"
                                                            data-id="{{ $comment->id }}" title="Approuver">
                                                            <i class="fas fa-check me-1"></i> 
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger reject-comment d-flex align-items-center"
                                                            data-id="{{ $comment->id }}" title="Rejeter">
                                                            <i class="fas fa-times me-1"></i> 
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn btn-sm btn-secondary d-flex align-items-center"
                                                            disabled title="Déjà traité">
                                                            <i class="fas fa-check-double me-1"></i> 
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        class="btn btn-sm btn-info view-comment d-flex align-items-center"
                                                        data-content="{{ $comment->contenu }}"
                                                        data-book="{{ optional($comment->ouvrage)->titre }}"
                                                        data-author="{{ optional($comment->utilisateur)->nom }}"
                                                        data-date="{{ $comment->created_at->format('d/m/Y') }}"
                                                        data-rating="{{ $comment->note }}"
                                                        title="Voir le commentaire complet">
                                                        <i class="fas fa-eye me-1"></i> 
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal pour afficher le commentaire complet -->
                    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title">
                                        <i class="fas fa-comment-dots text-primary"></i> Détails du commentaire
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-book text-primary mr-2"></i> Livre:</strong>
                                                <span id="modal-book"></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-user text-info mr-2"></i> Auteur:</strong>
                                                <span id="modal-author"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p><strong><i class="far fa-calendar-alt text-secondary mr-2"></i>
                                                    Date:</strong>
                                                <span id="modal-date"></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-star text-warning mr-2"></i> Note:</strong>
                                                <span id="modal-rating"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="comment-content p-3 bg-light rounded">
                                        <i class="fas fa-quote-left text-muted float-left mr-2"></i>
                                        <p id="modal-content" class="mb-0"></p>
                                        <i class="fas fa-quote-right text-muted float-right ml-2"></i>
                                        <div class="clearfix"></div>
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
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- CORE PLUGINS-->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>

    <!-- THEME SCRIPTS-->
    <script src="{{ url('assets/js/app.min.js') }}"></script>

    <!-- Script personnalisé -->
    <script>
        $(document).ready(function() {
            // Initialisation de DataTables
            $('#comments-table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                order: [
                    [3, 'desc']
                ],
                columnDefs: [{
                    targets: 6,
                    orderable: false,
                    searchable: false
                }]
            });

            // Voir le commentaire complet
            $(document).on('click', '.view-comment', function() {
                const button = $(this);
                const content = button.data('content');
                const book = button.data('book');
                const author = button.data('author');
                const date = button.data('date');
                const rating = button.data('rating');

                console.log('Content:', content);
                console.log('Book:', book);
                console.log('Author:', author);
                console.log('Date:', date);
                console.log('Rating:', rating);

                $('#modalCommentContent').text(content);
                $('#modalBookTitle').text(book);
                $('#modalCommentAuthor').text(author);
                $('#modalCommentDate').text(date);

                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    starsHtml += `<i class="fa ${i <= rating ? 'fa-star' : 'fa-star-o'} text-warning"></i>`;
                }
                $('#modalCommentRating').html(starsHtml);

                $('#commentModal').modal('show');
            });

            // Approuver un commentaire
            $(document).on('click', '.approve-comment', function() {
                const id = $(this).data('id');
                const form = $('<form>', {
                    method: 'POST',
                    action: `/comments/${id}/approuve`
                });
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));
                form.appendTo('body').submit();
            });

            // Rejeter un commentaire
            $(document).on('click', '.reject-comment', function() {
                const id = $(this).data('id');
                const form = $('<form>', {
                    method: 'POST',
                    action: `/comments/${id}/reject`
                });
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));
                form.appendTo('body').submit();
            });
        });

        $(document).ready(function() {
            // Afficher le modal avec le commentaire complet
            $('.view-comment').click(function() {
                $('#modal-content').text($(this).data('content'));
                $('#modal-book').text($(this).data('book'));
                $('#modal-author').text($(this).data('author'));
                $('#modal-date').text($(this).data('date'));

                // Afficher la note sous forme d'étoiles
                let rating = $(this).data('rating');
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars +=
                        `<i class="fas ${i <= rating ? 'fa-star' : 'fa-star-half-alt'} text-warning"></i>`;
                }
                $('#modal-rating').html(stars + ` (${rating}/5)`);

                $('#commentModal').modal('show');
            });

            // Activer les tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
