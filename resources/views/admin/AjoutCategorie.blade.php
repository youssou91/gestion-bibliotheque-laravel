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
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <i class="fa fa-plus-circle mr-2"></i>
                            <h4 class="mb-0">Nouvelle Catégorie</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('categories.store') }}" method="POST">
                                @csrf
                                @if (request('parent'))
                                    <input type="hidden" name="parent_id" value="{{ request('parent') }}">
                                    <p>Vous créez une sous‑catégorie de
                                        <strong>{{ \App\Models\Categories::find(request('parent'))->nom }}</strong>
                                    </p>
                                @endif

                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Description (optionnel)</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save mr-1"></i>Enregistrer
                                </button>
                                <a href="{{ route('admin.classification') }}" class="btn btn-secondary">
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
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
