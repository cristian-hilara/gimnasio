<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // mensual, trimestral, anual
            $table->integer('duracion_dias'); // 30, 90, 365
            $table->decimal('precio', 8, 2);
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true); // activa/inactiva
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};
