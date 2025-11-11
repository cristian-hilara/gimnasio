<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <div class="sb-sidenav-menu-heading">Inicio</div>
                <a class="nav-link" href="{{ route('cliente.panel', ['id' => auth()->user()->id]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Mi Panel
                </a>

                <div class="sb-sidenav-menu-heading">Mi Cuenta</div>
                <a class="nav-link" href="{{route('cliente.perfil')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Perfil
                </a>

                <a class="nav-link" href="{{route('cliente.rutinas.index')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-dumbbell"></i></div>
                    Mis Rutinas
                </a>

                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Mi Progreso
                </a>

                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                    Actividades
                </a>

                <a class="nav-link" href="{{ route('cliente.chat') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                    Chatbtot Personal
                </a>

                <div class="sb-sidenav-menu-heading">Soporte</div>
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fas fa-question-circle"></i></div>
                    Ayuda
                </a>

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->nombre }}
        </div>
    </nav>
</div>
