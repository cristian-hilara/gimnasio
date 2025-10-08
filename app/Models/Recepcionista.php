<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Recepcionista extends Model
{
    protected $table = 'recepcionistas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    //protected $keyType = 'int';
    protected $fillable = [
        'id',
        'turno',
        'fecha_contratacion',
        'estado',
        'usuario_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function isActivo()
    {
        return $this->estado === 'activo';
    }
}
