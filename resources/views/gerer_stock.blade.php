<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gestion du Stock</title>
    <!-- GLOBAL STYLES -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- THEME STYLES -->
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">

                    {{-- En‑tête + bouton Ajouter --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h4 text-warning"><i class="fa fa-cubes mr-2"></i>Gestion du Stock</h1>
                        <a href="{{ url('stocks.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus mr-1"></i> Ajouter un Stock
                        </a>
                    </div>

                    {{-- Alert stock total --}}
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fa fa-info-circle mr-2"></i>
                        <span>Stock total : <strong>{{ $stocks->sum('quantite') }}</strong> ouvrages</span>
                    </div>

                    {{-- Tableau --}}
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="stock-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Titre</th>
                                            <th class="text-right">Disponible</th>
                                            <th class="text-right">Prix Achat</th>
                                            <th class="text-right">Prix Vente</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stocks as $stock)
                                            @php
                                                switch ($stock->statut) {
                                                    case 'En stock':
                                                        $badge = 'success';
                                                        break;
                                                    case 'Stock faible':
                                                        $badge = 'warning';
                                                        break;
                                                    case 'Rupture':
                                                        $badge = 'danger';
                                                        break;
                                                    default:
                                                        $badge = 'secondary';
                                                }
                                            @endphp
                                            <tr>
                                                <td>STK-{{ str_pad($stock->id, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ optional($stock->ouvrage)->titre ?? '—' }}</td>
                                                <td class="text-right">{{ $stock->quantite }}</td>
                                                <td class="text-right">$
                                                    {{ number_format($stock->prix_achat, 2, ',', ' ') }}</td>
                                                <td class="text-right">$
                                                    {{ number_format($stock->prix_vente, 2, ',', ' ') }}</td>
                                                <td><span
                                                        class="badge badge-{{ $badge }}">{{ $stock->statut }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info btn-view"
                                                        data-id="{{ $stock->id }}" title="Voir">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ url('stocks.edit', $stock) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
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
            @include('includes.footer')
        </div>
    </div>

    {{-- Modale “Voir” --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-primary">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-eye mr-2"></i>Détails du Stock</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modal-cover" src="{{ asset('assets/img/placeholder.png') }}" alt="Couverture"
                                class="img-fluid rounded shadow-sm mb-3">
                        </div>
                        <div class="col-md-8">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Ouvrage :</strong></span><span id="modal-title"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Quantité :</strong></span><span id="modal-quantite"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Prix Achat :</strong></span><span id="modal-achat"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Prix Vente :</strong></span><span id="modal-vente"></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>Statut :</strong></span><span id="modal-statut"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i
                            class="fa fa-times mr-1"></i>Fermer</button>
                </div>
            </div>
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

    <script>
        // Pré‑définition des URLs côté JS
        const API_PREFIX = @json(url('gestion/stocks'));
        const IMG_BASE_URL = @json(asset('assets/img'));
        const PLACEHOLDER_IMG = @json(asset('assets/img/placeholder.png'));

        $(function() {
            // DataTable only once
            $('#stock-table').DataTable({
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                columnDefs: [{
                        targets: [2, 3, 4],
                        className: 'text-right',
                        type: 'num'
                    },
                    {
                        targets: 6,
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            // Bouton Voir → AJAX
            $(document).on('click', '.btn-view', function() {
                const id = $(this).data('id');
                $.getJSON(`${API_PREFIX}/${id}`)
                    .done(data => {
                        // Si la relation ouvrage existe
                        let photo = data.ouvrage && data.ouvrage.photo ?
                            `${IMG_BASE_URL}/${data.ouvrage.photo}` :
                            PLACEHOLDER_IMG;

                        $('#modal-cover').attr('src', photo);
                        $('#modal-title').text(data.ouvrage?.titre || '—');
                        $('#modal-quantite').text(data.quantite || '—');
                        $('#modal-achat').text(data.prix_achat + ' $' || '—');
                        $('#modal-vente').text(data.prix_vente + ' $' || '—');
                        $('#modal-statut').text(data.statut || '—');

                        $('#viewModal').modal('show');
                    })
                    .fail((_, __, err) => {
                        console.error('Erreur AJAX', err);
                        alert('Impossible de charger le stock');
                    });
            });
        });
    </script>
</body>

</html>
