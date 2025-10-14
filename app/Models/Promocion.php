<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected $appends = ['tipo_texto', 'estado_vigencia'];


    public function membresias()
    {
        return $this->belongsToMany(Membresia::class, 'promocion_membresia')
            ->withPivot('precio_promocional')
            ->withTimestamps();
    }

    /**
     * Verificar si la promoción está vigente
     */
    public function getVigenteAttribute()
    {
        $hoy = Carbon::now();
        return $this->activa &&
            $hoy->greaterThanOrEqualTo($this->fecha_inicio) &&
            $hoy->lessThanOrEqualTo($this->fecha_fin);
    }

    /**
     * Obtener el estado de vigencia
     */
    public function getEstadoVigenciaAttribute()
    {
        $hoy = Carbon::today(); // más claro que now() si no usás hora

        if (!$this->activa) {
            return ['texto' => 'Inactiva', 'clase' => 'bg-secondary'];
        }

        if ($hoy->lt($this->fecha_inicio)) {
            return ['texto' => 'Próxima', 'clase' => 'bg-info'];
        }

        if ($hoy->gt($this->fecha_fin)) {
            return ['texto' => 'Vencida', 'clase' => 'bg-danger'];
        }
        
        return ['texto' => 'Vigente', 'clase' => 'bg-success'];
    }


    

    /**
     * Obtener tipo en español
     */
    public function getTipoTextoAttribute()
    {
        $tipos = [
            'precio_especial' => 'Precio Especial',
            'descuento' => 'Descuento',
            'dias_extra' => 'Días Extra'
        ];

        return $tipos[$this->tipo] ?? $this->tipo;
    }
}
