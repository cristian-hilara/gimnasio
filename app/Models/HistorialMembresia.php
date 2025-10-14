<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialMembresia extends Model
{
    use HasFactory;

    protected $table = 'historial_membresias';

    protected $fillable = [
        'cliente_id',
        'membresia_id',
        'promocion_id',
        'fecha_inicio',
        'fecha_fin',
        'estado_membresia',
        'precio_original',
        'descuento_aplicado',
        'precio_final'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'precio_original' => 'decimal:2',
        'descuento_aplicado' => 'decimal:2',
        'precio_final' => 'decimal:2'
    ];

    /**
     * Relación con Cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con Membresía
     */
    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }

    /**
     * Relación con Promoción
     */
    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    /**
     * Relación con Pagos
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'historial_membresia_id');
    }

    /**
     * Verificar si está vigente
     */
    public function getEsVigenteAttribute()
    {
        return $this->estado_membresia === 'vigente' &&
            Carbon::now()->between($this->fecha_inicio, $this->fecha_fin);
    }

    /**
     * Días restantes
     */
    public function getDiasRestantesAttribute()
    {
        if ($this->estado_membresia !== 'vigente') {
            return 0;
        }

        $hoy = Carbon::now();
        if ($hoy->greaterThan($this->fecha_fin)) {
            return 0;
        }

        return $hoy->diffInDays($this->fecha_fin, false);
    }

    /**
     * Días totales de la membresía
     */
    public function getDiasTotalesAttribute()
    {
        return $this->fecha_inicio->diffInDays($this->fecha_fin);
    }

    /**
     * Porcentaje de progreso
     */
    public function getPorcentajeProgresoAttribute()
    {
        $hoy = Carbon::now();
        if ($hoy->lessThan($this->fecha_inicio)) {
            return 0;
        }
        if ($hoy->greaterThan($this->fecha_fin)) {
            return 100;
        }

        $diasTranscurridos = $this->fecha_inicio->diffInDays($hoy);
        $diasTotales = $this->dias_totales;

        return round(($diasTranscurridos / $diasTotales) * 100);
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        $estados = [
            'vigente' => ['texto' => 'Vigente', 'clase' => 'bg-success'],
            'vencida' => ['texto' => 'Vencida', 'clase' => 'bg-danger'],
            'suspendida' => ['texto' => 'Suspendida', 'clase' => 'bg-warning']
        ];

        return $estados[$this->estado_membresia] ?? ['texto' => 'Desconocido', 'clase' => 'bg-secondary'];
    }

    /**
     * Calcular porcentaje de descuento
     */
    public function getPorcentajeDescuentoAttribute()
    {
        if ($this->precio_original > 0 && $this->descuento_aplicado > 0) {
            return round(($this->descuento_aplicado / $this->precio_original) * 100, 2);
        }
        return 0;
    }
}
