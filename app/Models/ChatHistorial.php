<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistorial extends Model
{
    use HasFactory;

    protected $table = 'chat_historial';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cliente_id',
        'mensaje',
        'respuesta',
    ];
}
