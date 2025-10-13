<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;


    protected $table = 'actividades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre', 
        'descripcion', 
        'tipo_actividad_id'
    ];

    public function tipoActividad()
    {
        return $this->belongsTo(TipoActividad::class, 'tipo_actividad_id');
    }

    public function horarios()
    {
        return $this->hasMany(ActividadHorario::class, 'actividad_id');
    }

    /**
     * Relación con Instructor
     */


   
}
