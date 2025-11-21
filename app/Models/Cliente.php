<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    public $incrementing = true;
    //protected $keyType = 'int';
    protected $fillable = [
        'id',
        'peso',
        'altura',
        'codigoQR',
        'estado',
        'usuario_id'

    ];
    protected $casts = [
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function isActivo()
    {
        return $this->estado === 'activo';
    }


    public function getImcAttribute()
    {
        if ($this->peso && $this->altura && $this->altura > 0) {
            return round($this->peso / ($this->altura * $this->altura), 2);
        }
        return null;
    }

    /**
     * Obtener clasificación de IMC
     */
    public function getClasificacionImcAttribute()
    {
        $imc = $this->imc;

        if (!$imc) return 'No disponible';

        if ($imc < 18.5) return 'Bajo peso';
        if ($imc < 25) return 'Normal';
        if ($imc < 30) return 'Sobrepeso';
        return 'Obesidad';
    }

    public function historialMembresias()
    {
        return $this->hasMany(HistorialMembresia::class);
    }

    // para las rutinas
    public function rutinas()
    {
        return $this->hasMany(Rutina::class);
    }


    /**
     * Relación con Asistencias
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    /**
     * Obtener membresía vigente actual
     */
    public function getMembresiaVigenteAttribute()
    {
        return $this->historialMembresias()
            ->where('estado_membresia', 'vigente')
            ->whereDate('fecha_fin', '>=', today())
            ->first();
    }

    /**
     * Verificar si tiene membresía vigente
     */
    public function tieneMembresiaVigente()
    {
        return $this->historialMembresias()
            ->where('estado_membresia', 'vigente')
            ->whereDate('fecha_fin', '>=', today())
            ->exists();
    }

    /**
     * Última asistencia
     */
    public function ultimaAsistencia()
    {
        return $this->hasOne(Asistencia::class)->latestOfMany();
    }

    /**
     * Asistencia de hoy
     */
    public function asistenciaHoy()
    {
        return $this->hasOne(Asistencia::class)
            ->whereDate('fecha', today())
            ->latest();
    }

    public function actividades()
    {
        return $this->belongsToMany(ActividadHorario::class, 'actividad_cliente', 'cliente_id', 'actividad_horario_id')
            ->with('actividad', 'instructor', 'sala')
            ->withTimestamps();
    }

    public function objetivo()
    {
        return $this->belongsTo(Objetivo::class);
    }
}
