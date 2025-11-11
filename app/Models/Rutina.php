<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    use HasFactory;
    protected $table = 'rutinas';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'cliente_id',
        'instructor_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

        // Rutina → tiene muchos Ejercicios (a través de rutina_ejercicio)
    public function ejercicios()
    {
        return $this->belongsToMany(Ejercicio::class, 'rutina_ejercicio')->withPivot('series','repeticiones','peso','dia_semana')
            ->withTimestamps();
    }
}
