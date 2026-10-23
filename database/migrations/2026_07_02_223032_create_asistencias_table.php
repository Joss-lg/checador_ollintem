<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->date('fecha');
            $table->timestamps();

            // Índices para las consultas más frecuentes del sistema:
            // - Buscar la asistencia activa de un becario en la fecha de hoy
            // - Filtrar historial por becario y fecha
            $table->index(['user_id', 'fecha']);

            // - Cierre automático: buscar jornadas sin salida del día actual
            $table->index(['fecha', 'hora_salida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
