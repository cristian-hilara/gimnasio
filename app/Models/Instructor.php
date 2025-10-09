<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Instructor extends Model
{
    protected $table = 'instructors';
    protected $primaryKey = 'id';
    public $incrementing = true;
    //protected $keyType = 'int';
    protected $fillable = [
        'id',
        'especialidad',
        'experiencia',
        'estado',
        'usuario_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function getNombreCompletoAttribute()
    {
        return $this->usuario->nombre . ' ' . $this->usuario->apellido;
    }

    /**
     * Obtener foto o avatar por defecto
     */
    public function getFotoUrlAttribute()
    {
        if ($this->usuario && $this->usuario->foto) {
            return asset('storage/' . $this->usuario->foto);
        }
        return asset('img/default-avatar.png');
    }
}
