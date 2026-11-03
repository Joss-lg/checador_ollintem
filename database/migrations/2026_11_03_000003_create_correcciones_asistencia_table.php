<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bitácora de correcciones manuales de jornadas desde el historial.
 * Las columnas motivo_correccion/corregido_* de `asistencias` solo guardan
 * la última corrección; aquí queda cada una con sus horas antes y después.
 * Tabla nueva: no modifica ninguna tabla existente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('correcciones_asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asistencia_id')->constrained('asistencias')->cascadeOnDelete();
            $table->foreignId('corregido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->time('entrada_antes')->nullable();
            $table->time('salida_antes')->nullable();
            $table->time('entrada_despues')->nullable();
            $table->time('salida_despues')->nullable();
            $table->string('motivo');
            $table->timestamps();

            $table->index(['asistencia_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('correcciones_asistencia');
    }
};
