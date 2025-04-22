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
    @include('includes.header')
    @include('includes.sidebar')
    <div class="content-wrapper">
        <div class="page-content fade-in-up">
            <div class="container-fluid">
                <div class="card mb-3">
                    <div class="card">
                        <div class="card-header bg-warning text-white d-flex align-items-center">
                            <i class="fa fa-edit mr-2"></i>
                            <h4 class="mb-0">Modifier la Catégorie</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('categories.update', $cat->id) }}" method="POST">
                                @csrf
                                @method('POST')
                                {{-- Si la catégorie est une sous‑catégorie, afficher la parente --}}
                                @if ($cat->parent_id)
                                    <input type="hidden" name="parent_id" value="{{ $cat->parent_id }}">
                                    <p>Vous modifiez une sous‑catégorie de
                                        <strong>{{ $cat->parent->nom }}</strong>
                                    </p>
                                @endif

                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" class="form-control"
                                        value="{{ old('nom', $cat->nom) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description (optionnel)</label>
                                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $cat->description) }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-save mr-1"></i>Mettre à jour
                                </button>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                    Annuler
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>
    <!-- SCRIPTS JS -->
    <script src="{{ url('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/vendors/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
</body>

</html>
