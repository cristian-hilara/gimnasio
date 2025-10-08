<?php

use App\Http\Controllers\AdministradorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;

use App\Http\Controllers\Auth\homeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\UsuarioController;

use App\Models\Administrador;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\roleController;

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

// Redirección inteligente
Route::get('/', [RedirectController::class, 'index'])->name('redirect')->middleware('auth');

// Autenticación
Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

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

// Rutas adicionales para administrador
Route::prefix('administrador')->middleware('auth')->group(function () {
    Route::get('/', [AdministradorController::class, 'index'])->name('administrador.index');
    Route::get('/create', [AdministradorController::class, 'create'])->name('administrador.create');
    Route::post('/', [AdministradorController::class, 'store'])->name('administrador.store');
    Route::get('/{id}', [AdministradorController::class, 'edit'])->name('administrador.edit');
    Route::put('/{id}', [AdministradorController::class, 'update'])->name('administrador.actualizar');
    Route::delete('/{id}', [AdministradorController::class, 'destroy'])->name('administrador.eliminar');
    Route::get('/buscar-usuarios', [AdministradorController::class, 'buscarUsuarios'])->name('buscar.usuarios');
});

// Rutas adicionales
Route::get('/recepcionista', [RecepcionistaController::class, 'index'])->name('recepcionista.index')->middleware('auth');


// Rutas de Clientes
Route::resource('clientes', ClienteController::class);
//Rutas instructores
Route::resource('instructores', InstructorController::class);
//tutas recepcionista
Route::resource('recepcionistas', RecepcionistaController::class);

// Ruta adicional para generar QR
Route::get('clientes/{id}/qr', [ClienteController::class, 'generateQR'])->name('clientes.qr');

// Páginas de error
Route::view('/401', 'pages.401');
Route::view('/404', 'pages.404');
Route::view('/500', 'pages.500');
Route::view('/errors/cliente_no_registrado', 'errors.cliente_no_registrado')->name('errors.cliente_no_registrado');
Route::view('/errors/instructor_no_registrado', 'errors.instructor_no_registrado')->name('errors.instructor_no_registrado');
Route::view('/errors/recepcionista_no_registrado', 'errors.recepcionista_no_registrado')->name('errors.recepcionista_no_registrado');
