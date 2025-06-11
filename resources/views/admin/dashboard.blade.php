<!DOCTYPE html>
<html lang="en">
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Admincast </title>
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-success color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">{{ $empruntsMois }}</h2>
                                    <div class="m-b-5">EMPRUNTS DU MOIS</div>
                                    <i class="fa fa-book widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>{{ $empruntsSemaine }} cette semaine</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-info color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">{{ $retards }}</h2>
                                    <div class="m-b-5">RETARDS</div>
                                    <i class="fa fa-exclamation-triangle widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>À traiter</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-warning color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">{{ $empruntsSemaine }}</h2>
                                    <div class="m-b-5">EMPRUNTS SEMAINE</div>
                                    <i class="fa fa-calendar widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>Activité récente</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="ibox bg-danger color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-2 font-strong" title="{{ $topOuvrage }}" style="font-size: 1.2rem;">
                                        {{ Str::words($topOuvrage, 3, '...') }}
                                    </h2>
                                    <div class="m-b-5">TOP OUVRAGE ({{ $topEmprunts }} emprunts)</div>
                                    <i class="fa fa-star widget-stat-icon"></i>
                                    <div><i class="fa fa-level-up m-r-5"></i><small>Plus populaire</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header">Évolution des emprunts (30 jours)</div>
                                <div class="card-body">
                                    <canvas id="empruntsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header">Répartition par statut</div>
                                <div class="card-body">
                                    <canvas id="statutChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between">
                            <h5>Derniers emprunts</h5>
                            <div class="btn-group btn-sm">
                                <button class="btn btn-outline-secondary">Aujourd'hui</button>
                                <button class="btn btn-outline-secondary">7 jours</button>
                                <button class="btn btn-outline-secondary">30 jours</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover" id="empruntsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Utilisateur</th>
                                        <th>Ouvrage</th>
                                        <th>Statut</th>
                                        <th>Retour prévu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lastEmprunts as $emprunt)
                                    <tr>
                                        <td>#EMP-{{ str_pad($emprunt->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $emprunt->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $emprunt->utilisateur->nom ?? 'Inconnu' }}</td>
                                        <td>
                                            {{ $emprunt->ouvrage->titre ?? 'Inconnu' }}
                                        <td>
                                            @if($emprunt->statut == 'retourne')
                                                <span class="badge badge-success">Retourné</span>
                                            @elseif($emprunt->date_retour < now())
                                                <span class="badge badge-danger">En retard</span>
                                            @else
                                                <span class="badge badge-info">En cours</span>
                                            @endif
                                        </td>
                                        <td>{{ $emprunt->date_retour->format('d/m/Y') }}</td>
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

    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('./assets/js/scripts/dashboard_1_demo.js') }}" type="text/javascript"></script>
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
    <script>
        $(function() {
            const ctx1 = document.getElementById('empruntsChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($dailyDates),
                    datasets: [{
                        label: 'Emprunts quotidiens',
                        data: @json($dailyData),
                        tension: 0.4,
                        borderColor: '#4e73df',
                        fill: true,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)'
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
            const ctx2 = document.getElementById('statutChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: @json($statutLabels),
                    datasets: [{
                        data: @json($statutData),
                        backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e']
                    }]
                }
            });
            $('#empruntsTable').DataTable({
                order: [[1, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
                }
            });
        });
    </script>
</body>
</html> 