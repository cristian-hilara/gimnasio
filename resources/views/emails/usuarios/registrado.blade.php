@component('mail::message')
# Bienvenido {{ $usuario->nombre }}

Tu cuenta ha sido creada exitosamente.

- **Correo:** {{ $usuario->email }}
- **Contraseña temporal:** {{ $passwordTemporal }}

> Por seguridad, deberás cambiar tu contraseña al iniciar sesión por primera vez.

@component('mail::button', ['url' => route('login')])
Iniciar sesión
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
