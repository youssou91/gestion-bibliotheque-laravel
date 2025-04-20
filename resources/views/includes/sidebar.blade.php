<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="./assets/img/admin-avatar.png" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong">James Brown</div><small>Administrator</small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a class="active" href="{{url('/')}}"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                    <span class="nav-label">Bibliotheque (Edit)</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <li>
                        <a href="{{url('/routeEditDesc')}}">Editer les descriptions</a>
                    </li>
                    <li>
                        <a href="{{url('/categories')}}">Classifier les ouvrages</a>
                    </li>
                    <li>
                        <a href="{{url('/routeValidComment')}}">Valider les commentaires</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                    <span class="nav-label">Bibliotheque (Gest)</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <li>
                        <a href="{{url('/routeGestCatalog')}}">Gerer le catalogue</a>
                    </li>
                    <li>
                        <a href="{{url('/routeGestStock')}}">Gerer le stock</a>
                    </li>
                    <li>
                        <a href="{{url('/routeSuiviVente')}}">Suivre les ventes</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bookmark"></i>
                    <span class="nav-label">Bibliotheque (Admin)</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <li>
                        <a href="{{url('/routeMaintienSite')}}">Maintenir le site</a>
                    </li>
                    <li>
                        <a href="{{url('/routeGestusers')}}">Gerer les utilisateurs</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>