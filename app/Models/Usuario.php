<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'foto',

        'estado',
        'requiere_cambio_contrasena',
        'fecha_registro',
    ];

    protected $hidden = [
        'password',
    ];


    public $timestamps = false; // Cambia a true si usas created_at y updated_at

    /**
     * Sobrescribir el campo de contraseña para autenticación.
     */

    protected $appends = ['foto_url']; // Para acceder como atributo

    // Genera la URL completa de la foto
    // Genera la URL completa de la foto
    public function getFotoUrlAttribute()
    {

        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-user.png');
    }


    // Guarda la foto en el storage y actualiza el modelo

    // ...existing code...
    public function guardarFoto($archivo)
    {
        $nombre = uniqid() . '.' . $archivo->getClientOriginalExtension();
        $archivo->storeAs('public/fotos', $nombre);
        $this->foto = 'fotos/' . $nombre;
        $this->save();
    }

    // ...existing code...

    // Elimina la foto del storage
    public function eliminarFoto()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            Storage::disk('public')->delete($this->foto);
        }
    }


    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Relación 1:1 con Administrador
     */
    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'usuario_id', 'id');
    }

    /**
     * Relación 1:1 con Recepcionista
     */
    public function recepcionista()
    {
        return $this->hasOne(Recepcionista::class, 'usuario_id', 'id');
    }

    /**
     * Relación 1:1 con Instructor
     */
    public function instructor()
    {
        return $this->hasOne(Instructor::class, 'usuario_id', 'id');
    }

    /**
     * Relación 1:1 con Cliente
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'usuario_id', 'id');
    }

    /**
     * Sobrescribir el campo de username para autenticación (opcional)
     */
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function isActivo()
    {
        return $this->estado === 'activo';
    }
}
