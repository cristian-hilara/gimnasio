<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;


    protected $table = 'asistencias';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'origen',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime:H:i:s',
        'hora_salida' => 'datetime:H:i:s'
    ];

    /**
     * Relación con Cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Obtener duración de la sesión
     */
    public function getDuracionAttribute()
    {
        if (!$this->hora_salida) {
            return null;
        }

        $entrada = Carbon::parse($this->hora_entrada);
        $salida = Carbon::parse($this->hora_salida);

        return $entrada->diff($salida)->format('%H:%I:%S');
    }

    /**
     * Verificar si está actualmente en el gimnasio
     */
    public function getEnGimnasioAttribute()
    {
        return $this->fecha->isToday() && is_null($this->hora_salida);
    }

    /**
     * Obtener badge del origen
     */
    public function getOrigenBadgeAttribute()
    {
        return $this->origen === 'qr'
            ? '<span class="badge bg-success"><i class="fas fa-qrcode"></i> QR</span>'
            : '<span class="badge bg-info"><i class="fas fa-keyboard"></i> Manual</span>';
    }
}
