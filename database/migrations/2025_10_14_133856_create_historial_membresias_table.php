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
        Schema::create('historial_membresias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('membresia_id')->constrained('membresias')->onDelete('restrict');
            $table->foreignId('promocion_id')->nullable()->constrained('promociones')->onDelete('set null');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado_membresia', ['vigente', 'vencida', 'suspendida'])->default('vigente');
            $table->decimal('precio_original', 8, 2);
            $table->decimal('descuento_aplicado', 8, 2)->default(0);
            $table->decimal('precio_final', 8, 2);
            $table->timestamps();

            $table->index(['cliente_id', 'estado_membresia']);
            $table->index(['fecha_fin', 'estado_membresia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_membresias');
    }
};
