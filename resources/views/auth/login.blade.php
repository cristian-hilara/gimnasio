<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - SB Admin</title>
    <link href="{{asset('css/template.css')}}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #103619ff 0%, #1ae32bff 100%);
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
        }
        .form-control {
            border-radius: 0.75rem;
        }
        .input-group-text {
            background: #f3f6fb;
            border-radius: 0.75rem 0 0 0.75rem;
        }
        .btn-primary {
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #103619ff 0%, #1ae32bff 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-user-lock fa-3x text-success mb-3"></i>
                            <h3 class="fw-bold">Iniciar Sesión</h3>
                        </div>
                        @if ($errors->any())
                            @foreach ($errors->all() as $item)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{$item}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                        @endif
                        <form action="/login" method="post">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                    <input class="form-control" id="inputEmail" type="email" name="email" placeholder="Correo electrónico" required autofocus/>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Contraseña" required/>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" name="remember" />
                                <label class="form-check-label" for="inputRememberPassword">Recordar contraseña</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a class="small text-decoration-none" href="password.html">¿Olvidaste tu contraseña?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3 text-white-50">
                    &copy; {{ date('Y') }} Gimnasio | Todos los derechos reservados
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>