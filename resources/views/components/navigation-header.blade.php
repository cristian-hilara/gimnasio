<nav class="sb-topnav navbar navbar-expand navbar-dark navbar-green">
    <div class="container-fluid">
        <!-- Navbar Brand con Logo-->
        <a class="navbar-brand ps-3 d-flex align-items-center" href="{{ route('redirect')}}">
            <img src="{{ asset('storage/fotos/logo.jpg') }}" alt="Logo StartGym" class="logo-img me-2">
            <span class="brand-text">StartGym</span>
        </a>
        
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group search-box">
                <input class="form-control search-input" type="text" placeholder="Buscar..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-search" id="btnNavbarSearch" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center user-menu" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        <img src="{{ auth()->user()->foto_url }}" alt="Foto de perfil" class="rounded-circle">
                    </div>
                    <span class="d-none d-lg-inline text-white user-name">{{ auth()->user()->nombre }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="#!">
                            <i class="fas fa-cog me-2"></i>Configuraciones
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#!">
                            <i class="fas fa-history me-2"></i>Registro de actividad
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{route('logout')}}">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>