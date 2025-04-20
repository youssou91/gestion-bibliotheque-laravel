<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Classification des Ouvrages</title>
    <!-- STYLES GLOBAUX -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <i class="fa fa-sitemap fa-lg mr-2"></i>
                            <h4 class="mb-0">Classification des Ouvrages</h4>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">
                                <i class="fa fa-plus mr-1"></i> Nouvelle Catégorie
                            </a>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="classification-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Catégorie</th>
                                            <th>Titres des ouvrages</th>
                                            <th class="text-center">Nombre d'ouvrages</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cats as $cat)
                                            <tr data-level="1">
                                                <td><i class="fa fa-folder text-primary mr-2"></i>{{ $cat->nom }}
                                                </td>
                                                <td>
                                                    @foreach ($cat->ouvrages as $ouvrage)
                                                        <span class="badge badge-info mr-1">{{ $ouvrage->titre }}</span>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">{{ $cat->ouvrages_count }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-success btn-view"
                                                        data-toggle="modal" data-target="#viewModal"
                                                        data-nom="{{ $cat->nom }}"
                                                        data-count="{{ $cat->ouvrages_count }}"
                                                        data-titres="{{ $cat->ouvrages->pluck('titre')->join('||') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('categories.edit', $cat->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @foreach ($cat->children as $sub)
                                                <tr data-level="2">
                                                    <td class="pl-4 bg-light"><i
                                                            class="fa fa-angle-right mr-2"></i><em>{{ $sub->nom }}</em>
                                                    </td>
                                                    <td class="bg-light">
                                                        @foreach ($sub->ouvrages as $ouvrage)
                                                            <span
                                                                class="badge badge-secondary mr-1">{{ $ouvrage->titre }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center bg-light">{{ $sub->ouvrages_count }}</td>
                                                    <td class="text-center bg-light">
                                                        <button type="button" class="btn btn-sm btn-success btn-view"
                                                            data-toggle="modal" data-target="#viewModal"
                                                            data-nom="{{ $sub->nom }}"
                                                            data-count="{{ $sub->ouvrages_count }}"
                                                            data-titres="{{ $sub->ouvrages->pluck('titre')->join('||') }}">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <form action="{{ route('categories.destroy', $sub->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Modal de visualisation --}}
                            <div class="modal fade" id="viewModal" tabindex="-1" role="dialog"
                                aria-labelledby="viewModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel">Détails de la catégorie</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Fermer">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nom :</strong> <span id="modal-nom"></span></p>
                                            <p><strong>Nombre d'ouvrages :</strong> <span id="modal-count"></span></p>
                                            <p><strong>Titres :</strong></p>
                                            <ul id="modal-titres" class="pl-3"></ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @push('styles')
                            <style>
                                tr[data-level="2"] td {
                                    background-color: #f8f9fa;
                                    font-style: italic;
                                }
                            </style>
                        @endpush

                        @push('scripts')
                            <script>
                                $(document).ready(function() {
                                    // Masquer les sous-catégories au chargement
                                    $('tr[data-level="2"]').hide();

                                    // Clic sur ligne parent
                                    $('#classification-table').on('click', 'tr[data-level="1"]', function() {
                                        $(this).nextUntil('tr[data-level="1"]').toggle();
                                    });

                                    // Remplir et ouvrir modal
                                    $('.btn-view').on('click', function() {
                                        let titles = $(this).data('titres').split('||');
                                        $('#modal-nom').text($(this).data('nom'));
                                        $('#modal-count').text($(this).data('count'));
                                        let $ul = $('#modal-titres').empty();
                                        titles.forEach(function(t) {
                                            $('<li>').text(t).appendTo($ul);
                                        });
                                    });
                                });
                            </script>
                        @endpush

                    </div>
                </div>
                @include('includes.footer')

            </div>
        </div>

        <!-- SCRIPTS JS -->
        <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Cacher les sous-catégories au chargement
                $('tr[data-level="2"]').hide();

                // Toggle sous-catégories au clic sur ligne parent
                $('#classification-table tbody').on('click', 'tr[data-level="1"]', function() {
                    $(this).nextUntil('tr[data-level="1"]').toggle();
                });
            });
        </script>
        @stack('scripts')

        <style>
            /* Style badges et fonds */
            .badge-info {
                background-color: #007bff;
            }

            tr[data-level="2"] em {
                font-style: italic;
            }

            tr[data-level="2"] td {
                background-color: #f8f9fa;
            }
        </style>
</body>

</html>
