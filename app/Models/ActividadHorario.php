<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadHorario extends Model
{
    use HasFactory;


    protected $table = 'actividad_horarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'actividad_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'cupo_maximo',
        'estado',
        'instructor_id',
        'sala_id'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i'
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }


    ///////////
    /**
     * Accesorio para calcular duración en minutos
     */

    public function getDiaNombreAttribute()
    {
        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miércoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sábado' => 'Sábado',
            'domingo' => 'Domingo'
        ];

        return $dias[$this->dia_semana] ?? $this->dia_semana;
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        return $this->estado
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-danger">Inactivo</span>';
    }

    public function getDuracionMinutosAttribute()
    {
        return Carbon::parse($this->hora_inicio)->diffInMinutes(Carbon::parse($this->hora_fin));
    }
}
