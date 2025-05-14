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

                    <!-- Statistiques -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">En attente</h6>
                                    <h3 class="text-warning">{{ $pendingCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Approuvés (30 derniers jours)</h6>
                                    <h3 class="text-success">{{ $approuve30 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="text-muted">Rejetés (30 derniers jours)</h6>
                                    <h3 class="text-danger">{{ $rejete30 }}</h3>
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
                                            <td>
                                                <div class="comment-preview" data-content="{{ $comment->contenu }}">
                                                    {{ Str::words($comment->contenu, 10, '...') }}
                                                </div>
                                            </td>
                                            <td>{{ optional($comment->ouvrage)->titre ?? '—' }}</td>
                                            <td>{{ optional($comment->utilisateur)->nom ?? '—' }}</td>
                                            <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                            <td style="text-align: center; width: 100px">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa {{ $i <= $comment->note ? 'fa-star' : 'fa-star-o' }} text-warning"></i>
                                                @endfor
                                            </td>
                                            <td>
                                                @php
                                                    $statut = $comment->statut;
                                                    if ($statut === 'en_attente') {
                                                        $label = 'En attente';
                                                        $class = 'warning';
                                                    } elseif ($statut === 'approuve') {
                                                        $label = 'Approuvé';
                                                        $class = 'success';
                                                    } elseif ($statut === 'rejete') {
                                                        $label = 'Rejeté';
                                                        $class = 'danger';
                                                    } else {
                                                        $label = 'En attente';
                                                        $class = 'warning';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $class }}">{{ $label }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    @if ($comment->statut === 'en_attente')
                                                        <button type="button" class="btn btn-sm  m-2  btn-success approve-comment" data-id="{{ $comment->id }}" title="Approuver">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger m-2  reject-comment" data-id="{{ $comment->id }}" title="Rejeter">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary m-2" disabled title="Déjà traité">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-info view-comment m-2"
                                                        data-content="{{ $comment->contenu }}"
                                                        data-book="{{ optional($comment->ouvrage)->titre }}"
                                                        data-author="{{ optional($comment->utilisateur)->nom }}"
                                                        data-date="{{ $comment->created_at->format('d/m/Y') }}"
                                                        data-rating="{{ $comment->note }}"
                                                        title="Voir le commentaire complet">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal unique -->
                    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">
                                        <i class="fa fa-comment mr-2"></i>Détails du commentaire
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Commentaire :</strong></p>
                                    <p id="modalCommentContent" class="text-justify"></p>
                                    <p><strong>Livre :</strong> <span id="modalBookTitle"></span></p>
                                    <p><strong>Auteur :</strong> <span id="modalCommentAuthor"></span></p>
                                    <p><strong>Date :</strong> <span id="modalCommentDate"></span></p>
                                    <p><strong>Note :</strong> <span id="modalCommentRating"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="fa fa-times mr-2"></i>Fermer
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
    $(document).ready(function () {
        // Initialisation de DataTables
        $('#comments-table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
            },
            order: [[3, 'desc']],
            columnDefs: [{
                targets: 6,
                orderable: false,
                searchable: false
            }]
        });

        // Voir le commentaire complet
        $(document).on('click', '.view-comment', function () {
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
        $(document).on('click', '.approve-comment', function () {
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
        $(document).on('click', '.reject-comment', function () {
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
</script>
