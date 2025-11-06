<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- Inicio -->
                <div class="sb-sidenav-menu-heading">
                    <i class="fas fa-home me-2"></i>Inicio
                </div>
                <a class="nav-link" href="{{route('redirect')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Panel de Control
                </a>

                <!-- Módulos -->
                <div class="sb-sidenav-menu-heading">
                    <i class="fas fa-th-large me-2"></i>Módulos
                </div>

                <a class="nav-link" href="{{route('usuarios.index')}}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Usuarios
                </a>

                @can('ver-role')
                <a class="nav-link" href="{{route('roles.index')}}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    Roles y Permisos
                </a>
                @endcan

                <!-- Gestión de Usuarios -->
                <div class="sb-sidenav-menu-heading">
                    <i class="fas fa-user-cog me-2"></i>Gestión de Usuarios
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false" aria-controls="collapseUsuarios">
                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                    Tipos de Usuario
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUsuarios" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver-cliente')
                        <a class="nav-link" href="{{route('clientes.index')}}">
                            <i class="fas fa-user-tag me-2"></i>Clientes
                        </a>
                        @endcan
                        @can('ver-recepcionista')
                        <a class="nav-link" href="{{route('recepcionistas.index')}}">
                            <i class="fas fa-user-tie me-2"></i>Recepcionistas
                        </a>
                        @endcan
                        @can('ver-administrador')
                        <a class="nav-link" href="{{route('administradors.index')}}">
                            <i class="fas fa-user-shield me-2"></i>Administradores
                        </a>
                        @endcan
                        @can('ver-instructor')
                        <a class="nav-link" href="{{route('instructors.index')}}">
                            <i class="fas fa-user-graduate me-2"></i>Instructores
                        </a>
                        @endcan
                    </nav>
                </div>

                <!-- Gimnasio -->
                <div class="sb-sidenav-menu-heading">
                    <i class="fas fa-dumbbell me-2"></i>Gimnasio
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGimnasio" aria-expanded="false" aria-controls="collapseGimnasio">
                    <div class="sb-nav-link-icon"><i class="fas fa-dumbbell"></i></div>
                    Gestión de Actividades
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseGimnasio" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('actividad_horarios.index')}}">
                            <i class="fas fa-running me-2"></i>Actividades
                        </a>
                        <a class="nav-link" href="{{route('salas.index')}}">
                            <i class="fas fa-door-open me-2"></i>Salas
                        </a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMembresias" aria-expanded="false" aria-controls="collapseMembresias">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-id-card-clip"></i></div>
                    Gestión de Membresías
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseMembresias" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('membresias.index')}}">
                            <i class="fas fa-id-card me-2"></i>Membresias
                        </a>
                        <a class="nav-link" href="{{route('promociones.index')}}">
                            <i class="fas fa-tags me-2"></i>Promociones
                        </a>
                    </nav>

                </div>


                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInscripciones" aria-expanded="false" aria-controls="collapseInscripciones">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-id-card-clip"></i></div>
                    Inscripciones
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseInscripciones" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('historial-membresias.index') }}">
                            <i class="fas fa-id-card me-2"></i>Historial de Membresías
                        </a>
                        <a class="nav-link" href="{{ route('pagos.index') }}">
                            <i class="fas fa-money-check-alt me-2"></i>Pagos
                        </a>
                        <a class="nav-link" href="{{ route('inscripciones.create') }}">
                            <i class="fas fa-user-plus me-2"></i>Registrar Inscripción
                        </a>
                    </nav>
                </div>

                <a class="nav-link" href="{{route('asistencias.index')}}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Asistencias
                </a>

                <a class="nav-link" href="{{route('chat.index')}}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-robot"></i></div>
                    Chat con Gemini AI
                </a>




            </div>
        </div>

        <!-- Footer del Sidebar -->
        <div class="sb-sidenav-footer">
            <div class="user-footer-card">
                <div class="user-avatar-small">
                    <img src="{{ auth()->user()->foto_url }}" alt="Avatar" class="rounded-circle">
                </div>
                <div class="user-info">
                    <div class="small text-muted">Conectado como:</div>
                    <div class="user-name-footer">{{auth()->user()->nombre}}</div>
                </div>
            </div>
        </div>
    </nav>
</div>