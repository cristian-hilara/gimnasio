<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Recuperar contraseña</title>
    <link href="{{asset('css/template.css')}}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #103619ff 0%, #1ae32bff 100%);">
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-envelope-circle-check fa-3x text-success mb-3"></i>
                            <h3 class="fw-bold">Recuperar contraseña</h3>
                            <p class="text-muted">Ingresa tu correo y te enviaremos un enlace de recuperación.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Enviar enlace</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">Volver al login</a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3 text-white-50">
                    &copy; {{ date('Y') }} Gimnasio | Todos los derechos reservados
                </div>
            </div>
        </div>
    </div>
</body>
</html>
