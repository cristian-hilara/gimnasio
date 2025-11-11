<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'grupo_muscular',

    ];

    // Ejercicio puede estar en muchas Rutinas
    public function rutinas()
    {
        return $this->belongsToMany(Rutina::class, 'rutina_ejercicio')
            ->withPivot('series', 'repeticiones', 'peso', 'dia_semana')
            ->withTimestamps();
    }
}
