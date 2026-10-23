<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pausas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('asistencia_id')->nullable()->constrained();
            $table->time('inicio_pausa')->nullable();
            $table->time('fin_pausa')->nullable();
            $table->string('motivo');
            $table->date('fecha');
            $table->timestamps();

            // Índice para buscar pausas activas de una jornada (fin_pausa IS NULL)
            // — se usa en cada request del becario y en el polling de estadoJornada()
            $table->index(['asistencia_id', 'fin_pausa']);

            // Índice para calcular pausas acumuladas por becario en una fecha
            $table->index(['user_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pausas');
    }
};
