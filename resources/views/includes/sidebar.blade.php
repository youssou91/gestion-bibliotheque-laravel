<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="{{ asset('assets/img/admin-avatar.png') }}" style="width: 45px; height: 45px; border-radius: 50%;" />
            </div>
            <div class="admin-info">
                <div class="font-strong">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</div>
                <small>{{ ucfirst(Auth::user()->role) }}</small>
            </div>
        </div>
        <ul class="side-menu metismenu" id="side-menu">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Empêcher la fermeture du menu au clic
                document.querySelectorAll('#side-menu .nav-2-level a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.stopPropagation(); // Empêche la propagation de l'événement
                    });
                });

                // Garder le menu ouvert si un sous-menu est actif
                document.querySelectorAll('#side-menu .nav-2-level').forEach(function(submenu) {
                    if (submenu.querySelector('a.active')) {
                        submenu.classList.add('in');
                        submenu.style.height = 'auto';
                        submenu.parentElement.classList.add('active');
                    }
                });
            });
        </script>
            <li>
                <a class="active" href="{{ route('admin.dashboard') }}"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            {{-- Menu Editeur - Visible pour les éditeurs et admins --}}
            @if(in_array(Auth::user()->role, ['editeur', 'admin','gestionnaire']))
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-book"></i>
                    <span class="nav-label">Gestion des Livres</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <li>
                        <a href="{{url('/routeEditDesc')}}">Editer les descriptions</a>
                    </li>
                    <li>
                        <a href="{{url('/classification')}}">Classifier les ouvrages</a>
                    </li>
                    <li>
                        <a href="{{url('/comments')}}">Valider les commentaires</a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Menu Gestionnaire - Visible pour les gestionnaires et admins --}}
            @if(in_array(Auth::user()->role, ['gestionnaire', 'admin']))
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                    <span class="nav-label">Gestion Commerciale</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <li>
                        <a href="{{ route('gestion.catalogue') }}">Gerer le catalogue</a>
                    </li>
                    <li>
                        <a href="{{ url('stocks.index') }}">Gerer le stock</a>
                    </li>
                    <li>
                        <a href="{{ url('ventes.index') }}">Suivre les ventes</a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Menu Admin - Visible uniquement pour les admins --}}
            @if(Auth::user()->role === 'admin')
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                    <span class="nav-label">Administration</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <li>
                        <a href="{{ route('admin.maintien') }}">Maintenir le site</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}">Gerer les utilisateurs</a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Bouton de déconnexion --}}
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="sidebar-item-icon fa fa-sign-out"></i>
                    <span class="nav-label">Déconnexion</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>