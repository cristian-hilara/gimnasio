<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *esta tabla picvote ya no es necesario asi que borralooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
     */
    public function up(): void
    {
        Schema::create('actividad_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('actividad_horario_id')->constrained('actividad_horarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_cliente');
    }
};
