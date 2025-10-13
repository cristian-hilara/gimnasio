<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;
    protected $table = 'salas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'nombre',
        'ubicacion',
        'capacidad',
        'estado'
    ];

 
    public function horarios()
    {
        return $this->hasMany(ActividadHorario::class, 'sala_id', 'id');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }
}
