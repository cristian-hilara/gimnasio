<div class="container mt-4">
    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif

    <h4>Perfil de instructor no encontrado</h4>
    <p>Tu cuenta tiene el rol de instructor, pero aún no está vinculada a un perfil registrado.
        Xfavor vaya con administracion para completar su registro.
    </p>


    <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Cerrar sesión y volver al login
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
        @csrf
    </form>

</div>