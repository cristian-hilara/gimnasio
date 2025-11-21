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
        Schema::create('objetivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Bajar de peso", "Tonificar", "Ganar masa"
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('objetivo_id')->nullable()->after('usuario_id');
            $table->foreign('objetivo_id')->references('id')->on('objetivos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['objetivo_id']);
            $table->dropColumn('objetivo_id');
        });
        Schema::dropIfExists('objetivos');
    }
};
