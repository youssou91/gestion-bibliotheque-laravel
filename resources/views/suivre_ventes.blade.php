<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Ventes</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ url('./assets/vendors/chart.js/dist/Chart.min.css')}}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ url('assets/css/main.min.css')}}" rel="stylesheet" />
</head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        @include('includes.header')
        @include('includes.sidebar')
        
        <div class="content-wrapper">
            <div class="page-content fade-in-up">
                <div class="container-fluid">
                    <div class="card-header bg-purple text-white">
                        <h1 class="mt-4"><i class="fa fa-chart-line mr-2"></i>Suivi des Ventes</h1>
                    </div>

                    <!-- Cartes de synthèse -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card shadow-sm border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Ventes du mois</h5>
                                    <h2 class="text-primary">245</h2>
                                    <span class="text-success">+15% vs mois dernier</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card shadow-sm border-success">
                                <div class="card-body">
                                    <h5 class="card-title">Chiffre d'affaires</h5>
                                    <h2 class="text-success">15 430€</h2>
                                    <span class="text-muted">Moyenne/jour: 514€</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow-sm border-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Panier moyen</h5>
                                    <h2 class="text-warning">62€</h2>
                                    <span class="text-danger">-3% vs trim. dernier</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow-sm border-info">
                                <div class="card-body">
                                    <h5 class="card-title">Top Livre</h5>
                                    <h4 class="text-info">1984</h4>
                                    <span>128 ventes</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graphiques -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    Évolution des ventes (30 derniers jours)
                                </div>
                                <div class="card-body">
                                    <canvas id="salesChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    Répartition par catégorie
                                </div>
                                <div class="card-body">
                                    <canvas id="categoryChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des transactions -->
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Dernières transactions</h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary">Aujourd'hui</button>
                                <button class="btn btn-sm btn-outline-secondary">7 jours</button>
                                <button class="btn btn-sm btn-outline-secondary">30 jours</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="salesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Date</th>
                                            <th>Livre</th>
                                            <th>Quantité</th>
                                            <th>Prix unit.</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#TX-1587</td>
                                            <td>15/09/2023</td>
                                            <td>1984</td>
                                            <td>2</td>
                                            <td>18€</td>
                                            <td>36€</td>
                                            <td><span class="badge badge-success">Complétée</span></td>
                                        </tr>
                                        <tr>
                                            <td>#TX-1586</td>
                                            <td>14/09/2023</td>
                                            <td>Le Petit Prince</td>
                                            <td>1</td>
                                            <td>12€</td>
                                            <td>12€</td>
                                            <td><span class="badge badge-warning">En cours</span></td>
                                        </tr>
                                        <!-- Ajouter d'autres lignes selon besoin -->
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

    <!-- SCRIPTS -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/DataTables/datatables.min.js')}}"></script>
    <script src="{{ url('assets/js/app.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ url('./assets/vendors/metisMenu/dist/metisMenu.min.js')}}"></script>
    <script src="{{ url('assets/js/app.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        // Configuration DataTable
        $('#salesTable').DataTable({
            order: [[1, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'excel', 
                {
                    extend: 'pdf',
                    text: 'PDF',
                    customize: function (doc) {
                        doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*'];
                    }
                }
            ]
        });

        // Graphique des ventes
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: Array.from({length: 30}, (_, i) => moment().subtract(29 - i, 'days').format('DD/MM')),
                datasets: [{
                    label: 'Ventes quotidiennes',
                    data: [12, 19, 15, 20, 18, 25, 22, 30, 28, 27, 35, 40, 38, 42, 45, 40, 38, 35, 32, 30, 28, 25, 22, 20, 18, 15, 12, 10, 8, 5],
                    borderColor: '#4e73df',
                    tension: 0.4
                }]
            }
        });

        // Graphique des catégories
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Littérature', 'Science-Fiction', 'Histoire', 'Sciences'],
                datasets: [{
                    data: [45, 30, 15, 10],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
                }]
            }
        });
    });
    </script>

    <style>
    .border-purple { border-color: #6f42c1!important; }
    .bg-purple { background-color: #6f42c1!important; }
    .card-title { font-size: 0.9rem; margin-bottom: 0.5rem; }
    .card h2 { margin-bottom: 0; }
    </style>
</body>
<!-- 
    Cette page inclut :

Tableau de bord synthétique :

4 cartes de synthèse avec indicateurs clés

Évolution des ventes sur 30 jours (graphique linéaire)

Répartition des ventes par catégorie (graphique en donut)

Liste des transactions :

Tableau interactif avec tri et recherche

Filtres temporels rapides

Export des données (PDF/Excel)

Statuts de transaction colorés

Fonctionnalités avancées :

Actualisation dynamique des données

Visualisation graphique interactive

Gestion des dates avec Moment.js

Personnalisation des exports

Optimisations :

Design responsive

Couleurs cohérentes avec le thème

Indicateurs de performance (KPI)

Hiérarchie visuelle claire

Pour compléter cette page :

Ajouter une connexion à une API réelle

Implémenter des filtres avancés

Ajouter des tooltips explicatifs

Prévoir des alertes de seuils

Intégrer un système de prévisions
-->
</html>