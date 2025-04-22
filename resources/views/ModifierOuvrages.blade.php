<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque - Ouvrages</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
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
                    <div class="card-header bg-info text-white">
                        <h1 class="mt-4"><i class="fa fa-book mr-2"></i>Gestion des Ouvrages</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="ibox-body">
                            <div class="ibox">
                                <div class="ibox-head">
                                    <div class="ibox-title">Modification d'ouvrages</div>
                                    {{-- affichage du message  --}}
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                </div>
                                <div class="ibox-body">
                                    <form action="{{ route('routeModifierOuvrage.update', $ouvrage->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST') {{-- Spoof PUT sur une route POST --}}

                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label>Titre</label>
                                                <input class="form-control" name="titre" type="text"
                                                    value="{{ old('titre', $ouvrage->titre) }}">
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label>Année</label>
                                                <input class="form-control" name="annee_publication" type="number"
                                                    value="{{ old('annee_publication', $ouvrage->annee_publication) }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label>Niveau</label>
                                                <select class="form-control" name="niveau">
                                                    <option value="" disabled>-- Choisir un niveau --</option>
                                                    @foreach (['Expert', 'Intermediaire', 'Debutant'] as $niveau)
                                                        <option value="{{ $niveau }}"
                                                            {{ old('niveau', $ouvrage->niveau) == $niveau ? 'selected' : '' }}>
                                                            {{ $niveau }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <label for="categorie_id">Catégorie</label>
                                                <select name="categorie_id" id="categorie_id" class="form-control"
                                                    required>
                                                    <option value="" disabled>-- Choisir une catégorie --</option>
                                                    @foreach ($categories as $categorie)
                                                        <option value="{{ $categorie->id }}"
                                                            {{ old('categorie_id', $ouvrage->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                                            {{ $categorie->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" rows="5">{{ old('description', $ouvrage->description) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Photo de couverture (laissez vide pour conserver l’actuelle)</label>
                                            <input class="form-control" name="photo" type="file">
                                            @if ($ouvrage->photo)
                                                <p>Photo actuelle :</p>
                                                <img src="{{ url('assets/img/' . $ouvrage->photo) }}" height="120"
                                                    width="130" class="rounded-circle" />
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">
                                                Mettre à jour
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('includes.footer')
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ url('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/vendors/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ url('assets/js/app.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#ouvrages-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                        targets: 0,
                        width: '5%'
                    },
                    {
                        targets: 3,
                        type: 'num'
                    },
                    {
                        targets: 4,
                        orderable: false
                    },
                    {
                        targets: 5,
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        })
    </script>
    <script>
        $(document).on('click', '.btn-view', function() {
            // On repère la <tr> parente
            const $tr = $(this).closest('tr');

            // On extrait depuis les <td> (index 1 = photo, 2 = titre, 3 = année, 4 = niveau)
            const photoUrl = $tr.find('td').eq(1).find('img').attr('src');
            const titre = $tr.find('td').eq(2).text().trim();
            const annee = $tr.find('td').eq(3).text().trim();
            const niveau = $tr.find('td').eq(4).text().trim();
            // Pour la catégorie et la description, il vous faudra soit :
            //  • ajouter deux <td> cachés dans le <tr> (avec classe .d-none) contenant ces infos,
            //  • ou faire un appel AJAX à votre contrôleur si ces données ne sont pas en colonne.

            // On injecte dans le modal
            $('#modal-photo').attr('src', photoUrl);
            $('#modal-titre').text(titre);
            $('#modal-annee').text(annee);
            $('#modal-niveau').text(niveau);
            // $('#modal-categorie').text(categorie);
            // $('#modal-description').text(description);
        });
    </script>

</body>

</html>
