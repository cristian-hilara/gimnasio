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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('historial_membresia_id')->constrained('historial_membresias')->onDelete('cascade');

            $table->date('fecha_pago');
            $table->decimal('monto', 8, 2); // igual a precio_final del historial
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'qr']);
            $table->string('referencia_pago')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
