<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'historial_membresia_id',
        'fecha_pago',
        'monto',
        'metodo_pago',
        'referencia_pago'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2'
    ];

    /**
     * Relación con Cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación con HistorialMembresia
     */
    public function historialMembresia()
    {
        return $this->belongsTo(HistorialMembresia::class, 'historial_membresia_id');
    }

    /**
     * Obtener nombre del método de pago
     */
    public function getMetodoPagoTextoAttribute()
    {
        $metodos = [
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'transferencia' => 'Transferencia',
            'qr' => 'QR'
        ];

        return $metodos[$this->metodo_pago] ?? $this->metodo_pago;
    }

    /**
     * Obtener ícono del método de pago
     */
    public function getMetodoPagoIconoAttribute()
    {
        $iconos = [
            'efectivo' => 'fa-money-bill-wave',
            'tarjeta' => 'fa-credit-card',
            'transferencia' => 'fa-exchange-alt',
            'qr' => 'fa-qrcode'
        ];

        return $iconos[$this->metodo_pago] ?? 'fa-dollar-sign';
    }

    /**
     * Monto formateado
     */
    public function getMontoFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->monto, 2);
    }
}
