<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock</title>
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
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h4 text-warning"><i class="fa fa-cubes mr-2"></i>Gestion du Stock</h1>
                    </div>   
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-plus-circle mr-2"></i>
                            <span>Ajouter un Stock</span>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('stocks.store') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="ouvrage_id">Ouvrage</label>
                                        <select name="ouvrage_id" id="ouvrage_id" class="form-control" required>
                                            <option value="" disabled selected>-- Choisir un ouvrage --</option>
                                            @foreach ($ouvrages as $ouvrage)
                                                <option value="{{ $ouvrage->id }}">{{ $ouvrage->titre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="quantite">Quantité</label>
                                        <input type="number" name="quantite" id="quantite" class="form-control"
                                            min="0" value="0" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="statut">Statut</label>
                                        <select name="statut" id="statut" class="form-control" required>
                                            <option value="En stock">En stock</option>
                                            <option value="Stock faible">Stock faible</option>
                                            <option value="Rupture">Rupture</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="prix_achat">Prix d'achat ($)</label>
                                        <input type="number" step="0.01" name="prix_achat" id="prix_achat"
                                            class="form-control" min="0" value="0.00" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="prix_vente">Prix de vente ($)</label>
                                        <input type="number" step="0.01" name="prix_vente" id="prix_vente"
                                            class="form-control" min="0" value="0.00" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save mr-1"></i>Enregistrer
                                </button>
                                <a href="{{ route('stocks.index') }}" class="btn btn-secondary ml-2">Annuler</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>
    <!-- SCRIPTS -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
</body>

</html>
