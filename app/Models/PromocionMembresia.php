<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocionMembresia extends Model
{
    use HasFactory;
    protected $table = 'promocion_membresia';
    protected $fillable = [
        'promocion_id',
        'membresia_id',
        'precio_promocional'
    ];

    protected $casts = [
        'precio_promocional' => 'decimal:2'
    ];

    /**
     * Relación con Promoción
     */
    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    /**
     * Relación con Membresía
     */
    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }
}
