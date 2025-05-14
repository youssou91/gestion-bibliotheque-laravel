{{-- resources/views/suivi_vente.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Ventes</title>
    <!-- GLOBAL STYLES -->
    <link href="{{ url('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <!-- PLUGIN STYLES -->
    <link href="{{ url('assets/vendors/chart.js/dist/Chart.min.css') }}" rel="stylesheet" />
    <!-- THEME STYLES -->
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    {{-- En‑tête --}}
                    <div class="card-header bg-purple text-white mb-4">
                        <h1 class="mt-4"><i class="fa fa-chart-line mr-2"></i>Suivi des Ventes</h1>
                    </div>

                    {{-- KPI --}}
                    <div class="row mb-4 g-4">
                        <div class="col-md-3">
                            <div class="card card-hover border-primary shadow-soft">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-chart-line fa-2x text-primary me-2"></i>
                                        <h5 class="card-title text-uppercase fw-bold mb-0 ml-2 text-bold">Ventes du mois</h5>
                                    </div>
                                    <h2 class="text-primary display-5 fw-bold mb-0">{{ $ventesMois }}</h2>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <div class="card card-hover border-success shadow-soft">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-dollar-sign fa-2x text-success me-2"></i>
                                        <h5 class="card-title text-uppercase fw-bold mb-0 ml-2 text-bold">Chiffre d'affaires</h5>
                                    </div>
                                    <h2 class="text-success display-5 fw-bold mb-0">{{ number_format($CA, 0, ',', ' ') }} $</h2>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <div class="card card-hover border-warning shadow-soft">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-shopping-basket fa-2x text-warning me-2"></i>
                                        <h5 class="card-title text-uppercase fw-bold mb-0 ml-2 text-bold">Panier moyen</h5>
                                    </div>
                                    <h2 class="text-warning display-5 fw-bold mb-0">{{ number_format($panierMoyen, 2, ',', ' ') }} $</h2>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <div class="card card-hover border-info shadow-soft">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-book-open fa-2x text-info me-2"></i>
                                        <h5 class="card-title text-uppercase fw-bold mb-0 mx-2 text-bold"> Top Livre </h5>
                                        {{-- <span class="text-muted small">({{ $topVentes }} ventes)</span> --}}
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="text-info display-5 fw-bold mb-0">{{ $topLivre }}</h6>
                                        <span class="text-info small mb-0">({{ $topVentes }} ventes)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Graphiques --}}
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header">Évolution des ventes (30 jours)</div>
                                <div class="card-body">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header">Répartition par catégorie</div>
                                <div class="card-body">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dernières transactions --}}
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between">
                            <h5>Dernières transactions</h5>
                            <div class="btn-group btn-sm">
                                <button class="btn btn-outline-secondary">Aujourd'hui</button>
                                <button class="btn btn-outline-secondary">7 jours</button>
                                <button class="btn btn-outline-secondary">30 jours</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover" id="salesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Livre</th>
                                        <th>Quantité</th>
                                        <th>Prix U.</th>
                                        <th>Total</th>
                                        {{-- <th>Statut</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lastLines as $item)
                                        <tr>
                                            <td>#TX-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td>{{ optional($item->ouvrage)->titre }}</td>
                                            <td>{{ $item->quantite }}</td>
                                            <td>{{ $item->prix_unitaire }} $</td>
                                            <td>{{ $item->quantite * $item->prix_unitaire }} $</td>
                                            {{-- <td><span class="badge badge-success">Complétée</span></td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <script>
        // Récupérées depuis le contrôleur
        const salesDates = @json($dailyDates);
        const salesData = @json($dailyData);
        const categoryNames = @json($catLabels);
        const categoryData = @json($catData);

        $(function() {
            // Graphique linéaire
            const ctx1 = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: salesDates,
                    datasets: [{
                        label: 'Ventes quotidiennes',
                        data: salesData,
                        tension: 0.4,
                        borderColor: '#4e73df',
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique doughnut
            const ctx2 = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: categoryNames,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
                    }]
                }
            });

            // DataTable
            $('#salesTable').DataTable({
                order: [
                    [1, 'desc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                },
                dom: 'Bfrtip',
                buttons: [
                    'excel',
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        customize: function(doc) {
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length).fill('*');
                        }
                    }
                ]
            });
        });
    </script>
    <style>
        .shadow-soft {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
    
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }
    
        .card {
            border-radius: 12px;
            border-width: 2px;
        }
    
        .card-title {
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
    </style>
</body>

</html>
