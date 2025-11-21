<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;

use App\Http\Controllers\Auth\homeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClienteActividadController;
use App\Http\Controllers\ClienteChatController;
use App\Http\Controllers\UsuarioController;

use App\Models\Administrador;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteNotificacionController;
use App\Http\Controllers\ClientePanelController;
use App\Http\Controllers\ClientePerfilController;
use App\Http\Controllers\EjercicioController;
use App\Http\Controllers\HistorialMembresiaController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\PromocionMembresiaController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\RutinaController;
use App\Http\Controllers\TipoActividadHorarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Página de inicio
Route::get('/', [homeController::class, 'index'])->name('home');

// Redirección inteligente
Route::get('/log', [RedirectController::class, 'index'])->name('redirect')->middleware('auth');

// Autenticación
Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

// Cambio de contraseña obligatorio
// Cambio obligatorio de contraseña
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'showChangeForm'])
        ->name('password.change.form');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])
        ->name('password.change.update');
});
// Recuperación de contraseña
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Panel compartido para ADMINISTRADOR y RECEPCIONISTA
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('ADMINISTRADOR')) {
        return app(AdministradorController::class)->dashboard();
    }

    if ($user->hasRole('RECEPCIONISTA')) {
        return app(RecepcionistaController::class)->dashboard();
    }

    abort(403);
})->name('dashboard')->middleware('auth');

// Paneles individuales
Route::get('/instructor/panel', [InstructorController::class, 'dashboard'])->name('instructor.panel')->middleware(['auth', 'role:INSTRUCTOR']);
Route::get('/cliente/panel', [ClienteController::class, 'dashboard'])->name('cliente.panel')->middleware(['auth', 'role:CLIENTE']);



// Recursos

Route::resource('usuarios', UsuarioController::class)->middleware(['auth', 'role:ADMINISTRADOR']);


Route::resource('roles', roleController::class)->middleware('auth');


//tutas de administrador
Route::resource('administradors', AdministradorController::class);

// CRUD de clientes
Route::resource('clientes', ClienteController::class);

// Perfil del cliente
Route::get('/clientes/{id}/perfil', [ClienteController::class, 'perfil'])->name('clientes.perfil');
Route::get('/clientes/{id}/qr/descargar', [ClienteController::class, 'descargarQR'])->name('clientes.qr.descargar');
Route::get('/clientes/{id}/tarjeta', [ClienteController::class, 'verTarjeta'])->name('clientes.tarjeta');
Route::get('/clientes/{id}/tarjeta/pdf', [ClienteController::class, 'generarTarjeta'])->name('clientes.tarjeta.pdf');
Route::post('/clientes/{id}/qr/regenerar', [ClienteController::class, 'regenerarQR'])->name('clientes.qr.regenerar');

//Rutas instructores
Route::resource('instructors', InstructorController::class);
//tutas recepcionista
Route::resource('recepcionistas', RecepcionistaController::class);

// Ruta adicional para generar QR
Route::get('clientes/{id}/qr', [ClienteController::class, 'generateQR'])->name('clientes.qr');


//gestion de actividades
// Rutas de salas
Route::resource('salas', App\Http\Controllers\SalaController::class);
// Rutas de actividades
Route::resource('actividades', ActividadController::class);
Route::resource('actividad_horarios', TipoActividadHorarioController::class);
Route::resource('tipos_actividad', App\Http\Controllers\TipoActividadController::class);


// Rutas de membresías
Route::resource('membresias', MembresiaController::class);
// Rutas de promociones
Route::resource('promociones', PromocionController::class);

// Rutas para gestionar la relación entre promociones y membresías
Route::prefix('promociones/{promocion}/membresias')->group(function () {
    Route::get('/', [PromocionMembresiaController::class, 'index'])->name('promocion-membresias.index');
    Route::get('/disponibles', [PromocionMembresiaController::class, 'disponibles'])->name('promocion-membresias.disponibles');
    Route::post('/', [PromocionMembresiaController::class, 'store'])->name('promocion-membresias.store');
    Route::put('/{membresia}', [PromocionMembresiaController::class, 'update'])->name('promocion-membresias.update');
    Route::delete('/{membresia}', [PromocionMembresiaController::class, 'destroy'])->name('promocion-membresias.destroy');
});



// Rutas de Inscripciones 
Route::prefix('inscripciones')->group(function () {
    Route::get('/create', [InscripcionController::class, 'create'])->name('inscripciones.create');
    Route::post('/', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::get('/membresia/{membresia}/precio', [InscripcionController::class, 'getPrecioMembresia'])
        ->name('inscripciones.precio');
});

// Rutas de Historial de Membresías
Route::resource('historial-membresias', HistorialMembresiaController::class)->except(['create', 'store']);

// Rutas adicionales para historial
Route::prefix('historial-membresias')->group(function () {
    Route::put('/{historialMembresia}/suspend', [HistorialMembresiaController::class, 'suspend'])->name('historial-membresias.suspend');
    Route::put('/{historialMembresia}/reactivate', [HistorialMembresiaController::class, 'reactivate'])->name('historial-membresias.reactivate');
    Route::get('/cliente/{cliente}', [HistorialMembresiaController::class, 'porCliente'])->name('historial-membresias.por-cliente');
    Route::post('/actualizar-estados', [HistorialMembresiaController::class, 'actualizarEstados'])->name('historial-membresias.actualizar-estados');
});

// Rutas de Pagos
Route::resource('pagos', PagoController::class);

// Rutas adicionales para pagos
Route::prefix('pagos')->group(function () {
    Route::get('/cliente/{cliente}', [PagoController::class, 'porCliente'])->name('pagos.por-cliente');
    Route::get('/cliente/{cliente}/historiales', [PagoController::class, 'getHistorialesCliente'])->name('pagos.historiales-cliente');
});



Route::prefix('asistencias')->group(function () {
    Route::get('/', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::post('/manual/{id}', [AsistenciaController::class, 'registrarManual'])->name('asistencias.registrarManual');
    Route::post('/salida/{id}', [AsistenciaController::class, 'registrarSalida'])->name('asistencias.registrarSalida');

    // Scanner QR
    Route::get('/scanner', [AsistenciaController::class, 'scanner'])->name('asistencias.scanner');
    Route::post('/qr', [AsistenciaController::class, 'registrarQR'])->name('asistencias.registrarQR');

    // Historial
    Route::get('/historial', [AsistenciaController::class, 'historial'])->name('asistencias.historial');

    // Verificar cliente
    Route::get('/verificar/{id}', [AsistenciaController::class, 'verificarCliente'])->name('asistencias.verificar');
});

//chatbot
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send.gemini'); // solo Gemini

Route::post('/chat', [ChatController::class, 'responder'])->name('chat.send'); // con lógica interna + Gemini


//perfil del cliente
Route::middleware(['auth'])->group(function () {

    Route::get('/cliente/panel', [ClientePanelController::class, 'index'])->name('cliente.panel');
    Route::get('/cliente/actividades', [ClientePanelController::class, 'actividades'])->name('cliente.actividades');
    Route::get('/cliente/membresia', [ClientePanelController::class, 'membresia'])->name('cliente.membresia');



    Route::get('/perfil', [ClientePerfilController::class, 'show'])->name('cliente.perfil');
    Route::get('/perfil/editar', [ClientePerfilController::class, 'edit'])->name('cliente.perfil.edit');
    Route::post('/perfil/editar', [ClientePerfilController::class, 'update'])->name('cliente.perfil.update');
    Route::get('actividad/clientes', [ClienteActividadController::class, 'index'])->name('cliente.actividad.horarios');


    Route::get('/rutinas', [RutinaController::class, 'index'])->name('cliente.rutinas.index');
    Route::get('/rutinas/create', [RutinaController::class, 'create'])->name('cliente.rutinas.create');
    Route::post('/rutinas', [RutinaController::class, 'store'])->name('cliente.rutinas.store');
    Route::get('/rutinas/{id}', [RutinaController::class, 'show'])->name('cliente.rutinas.show');
    Route::get('/rutinas/{id}/edit', [RutinaController::class, 'edit'])->name('cliente.rutinas.edit');
    Route::put('/rutinas/{id}', [RutinaController::class, 'update'])->name('cliente.rutinas.update');
    Route::delete('/rutinas/{id}', [RutinaController::class, 'destroy'])->name('cliente.rutinas.destroy');

    //chatbot clinente
    Route::get('/cliente/chat', [ClienteChatController::class, 'index'])->name('cliente.chat');
    Route::post('/cliente/chat/responder', [ClienteChatController::class, 'responder'])->name('cliente.chat.responder');
    //notificaciones cliente
    Route::get('/cliente/notificaciones', [ClienteNotificacionController::class, 'obtener'])->name('cliente.notificaciones');
});




//rutinas
Route::middleware(['auth', 'role:ADMINISTRADOR|INSTRUCTOR|RECEPCIONISTA'])->prefix('admin')->group(function () {
    Route::get('/clientes/{cliente}/rutinas', [RutinaController::class, 'indexAdmin'])->name('admin.clientes.rutinas.index');
    Route::get('/clientes/{cliente}/rutinas/create', [RutinaController::class, 'createAdmin'])->name('admin.clientes.rutinas.create');
    Route::post('/clientes/{cliente}/rutinas', [RutinaController::class, 'storeAdmin'])->name('admin.clientes.rutinas.store');
});


Route::middleware(['auth', 'role:ADMINISTRADOR|INSTRUCTOR|RECEPCIONISTA'])->group(function () {
    Route::resource('ejercicios', EjercicioController::class);
});
// Rutas de rutinas para clientes





















Route::get('/test-gemini', function () {
    $response = Http::post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'),
        [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Hola, ¿cómo estás?']
                    ]
                ]
            ]
        ]
    );

    return $response->json();
});

Route::get('/test-ssl', function () {
    $response = Http::get('https://www.google.com');
    return $response->status(); // Debería devolver 200
});

Route::get('/phpinfo', function () {
    phpinfo();
});




// Páginas de error
Route::view('/401', 'pages.401');
Route::view('/404', 'pages.404');
Route::view('/500', 'pages.500');
Route::view('/errors/cliente_no_registrado', 'errors.cliente_no_registrado')->name('errors.cliente_no_registrado');
Route::view('/errors/instructor_no_registrado', 'errors.instructor_no_registrado')->name('errors.instructor_no_registrado');
Route::view('/errors/recepcionista_no_registrado', 'errors.recepcionista_no_registrado')->name('errors.recepcionista_no_registrado');
