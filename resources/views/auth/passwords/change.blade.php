<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Cambiar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Cambiar contraseña</h2>
        <p>Por seguridad, debes cambiar tu contraseña antes de continuar.</p>

        <!-- Mensaje de advertencia -->
        <div class="alert alert-warning" style="display:none;">
            Aquí aparecerá el mensaje de advertencia
        </div>

        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input id="password" type="password" name="password" class="form-control" required>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
        </form>

    </div>
</body>

</html>