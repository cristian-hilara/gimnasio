<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Mail\UsuarioRegistradoMail;
use App\Models\Usuario;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
use Str;

class UsuarioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuario|crear-usuario|editar-usuario|eliminar-usuario', ['only' => ['index']]);
        $this->middleware('permission:crear-usuario', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-usuario', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-usuario', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Usuario::all();
        return view('Usuarios.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuario = Auth::user();

        if ($usuario->hasRole('ADMINISTRADOR')) {
            $roles = Role::all(); // Puede asignar todos
        } elseif ($usuario->hasRole('RECEPCIONISTA')) {
            $roles = Role::whereIn('name', ['CLIENTE', 'INSTRUCTOR'])->get(); // Solo estos dos
        } else {
            abort(403, 'No tienes permiso para registrar usuarios.');
        }

        return view('Usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        try {
        DB::beginTransaction();

        // Generar contraseña temporal aleatoria
        $passwordTemporal = Str::random(10);

        $request->merge([
            'password' => Hash::make($passwordTemporal),
            'fecha_registro' => now(),
            'requiere_cambio_contrasena' => true,
        ]);

        $user = Usuario::create($request->all());

        if ($request->hasFile('foto')) {
            $user->guardarFoto($request->file('foto'));
        }

        $user->assignRole($request->rol);

        // Enviar correo con credenciales temporales
        Mail::to($user->email)->send(new UsuarioRegistradoMail($user, $passwordTemporal));

        DB::commit();
        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado y credenciales enviadas.');
    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->route('usuarios.index')->with('error', 'Error al registrar: ' . $e->getMessage());
    }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        $user = Auth::user();
        $rolUsuario = $usuario->getRoleNames()->first();

        if ($user->hasRole('RECEPCIONISTA') && !in_array($rolUsuario, ['CLIENTE', 'INSTRUCTOR'])) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        if ($user->hasRole('ADMINISTRADOR')) {
            $roles = Role::all();
        } elseif ($user->hasRole('RECEPCIONISTA')) {
            $roles = Role::whereIn('name', ['CLIENTE', 'INSTRUCTOR'])->get();
        } else {
            abort(403, 'No tienes permiso para editar usuarios.');
        }

        return view('Usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        $user = Auth::user();
        $rolUsuario = $usuario->getRoleNames()->first();

        if ($user->hasRole('RECEPCIONISTA') && !in_array($rolUsuario, ['CLIENTE', 'INSTRUCTOR'])) {
            abort(403, 'No tienes permiso para actualizar este usuario.');
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Comprobar el password y aplicar hash
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                $data['requiere_cambio_contrasena'] = true; // obliga a cambiarla en el próximo login
            } else {
                unset($data['password']);
            }

            // Reemplazar foto si se subió
            if ($request->hasFile('foto')) {
                $usuario->guardarFoto($request->file('foto'));
                unset($data['foto']);
            }

            // Actualizar datos del usuario
            $usuario->update($data);

            // Verificar si está vinculado a algún módulo
            $estaVinculado = $usuario->cliente()->exists()
                || $usuario->recepcionista()->exists()
                || $usuario->administrador()->exists()
                || $usuario->instructor()->exists();

            // Solo actualizar rol si no está vinculado
            if (!$estaVinculado) {
                $usuario->syncRoles([$data['rol']]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $user = Auth::user();

        // Verificar si el usuario está vinculado a algún módulo
        $estaVinculado = $usuario->cliente()->exists()
            || $usuario->recepcionista()->exists()
            || $usuario->administrador()->exists()
            || $usuario->instructor()->exists();

        if ($estaVinculado) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Este usuario está vinculado como cliente, instructor, recepcionista o administrador y no puede ser eliminado.');
        }

        // Restricción para recepcionistas
        $rolUsuario = $usuario->getRoleNames()->first();
        if ($user->hasRole('RECEPCIONISTA') && !in_array($rolUsuario, ['CLIENTE', 'INSTRUCTOR'])) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        // Eliminar rol y foto
        $usuario->removeRole($rolUsuario);
        $usuario->eliminarFoto();

        // Eliminar usuario
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
