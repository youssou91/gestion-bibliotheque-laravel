<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Admincast bootstrap 4 &amp; angular 5 admin template, Шаблон админки | Dashboard</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css') }}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
               
            </div>
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card-header bg-purple text-white mb-4">
                        <h1 class="mt-4"><i class="fa fa-chart-line mr-2"></i>Suivi des Ventes</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-success color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong" style="font-size: 1.2rem;">
                                        {{-- {{ $ventesMois }} --}}
                                    </h2>
                                    <div class="m-b-5">VENTES DU MOIS</div>
                                    <i class="ti-shopping-cart widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>+12% vs mois précédent</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-info color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong" style="font-size: 1.2rem;">{{ number_format($CA, 0, ',', ' ') }} $</h2>
                                    <div class="m-b-5">CHIFFRE D'AFFAIRES</div>
                                    <i class="ti-bar-chart widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>+8% ce mois</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-warning color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong" style="font-size: 1.2rem;">{{ number_format($panierMoyen, 2, ',', ' ') }} $</h2>
                                    <div class="m-b-5">PANIER MOYEN</div>
                                    <i class="fa fa-money widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>+5% moyenne</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-danger color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-2 font-strong" title="{{ $topLivre }}" style="font-size: 1.2rem;">
                                        {{ Str::words($topLivre, 3, '...') }}
                                    </h2>

                                    <div class="m-b-5">TOP LIVRE ({{ $topVentes }} ventes)</div>
                                    <i class="ti-user widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>Best-seller</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/js/scripts/dashboard_1_demo.js') }}" type="text/javascript"></script>

    <script>
        const salesDates = @json($dailyDates);
        const salesData = @json($dailyData);
        const categoryNames = @json($catLabels);
        const categoryData = @json($catData);

        $(function() {
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
