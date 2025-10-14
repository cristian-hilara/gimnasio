<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;
    protected $table = 'membresias';
    protected $fillable=[
        'nombre',
        'descripcion',
        'precio',
        'duracion_dias',
        'estado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
        'duracion_dias' => 'integer'
    ];
    protected $appends = ['duracion_texto', 'precio_formateado'];


    /**
     * Relación con Promociones (muchos a muchos)
     */
    public function promociones()
    {
        return $this->belongsToMany(Promocion::class, 'promocion_membresia')
            ->withPivot('precio_promocional')
            ->withTimestamps();
    }

    /**
     * Obtener duración en formato legible
     */
    public function getDuracionTextoAttribute()
    {
        if ($this->duracion_dias == 30) return '1 Mes';
        if ($this->duracion_dias == 90) return '3 Meses';
        if ($this->duracion_dias == 180) return '6 Meses';
        if ($this->duracion_dias == 365) return '1 Año';
        return $this->duracion_dias . ' días';
    }

    /**
     * Obtener precio formateado
     */
    public function getPrecioFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->precio, 2);
    }
}
