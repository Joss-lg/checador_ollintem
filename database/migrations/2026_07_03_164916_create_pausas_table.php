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
            // foreignId para el usuario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // foreignId para la asistencia integrado directamente
            $table->foreignId('asistencia_id')
                ->nullable()
                ->after('user_id')
                ->constrained('asistencias')
                ->cascadeOnDelete();
            
            // Tiempos y motivo
            $table->time('inicio_pausa')->nullable();
            $table->time('fin_pausa')->nullable();
            $table->string('motivo');
            $table->date('fecha');
            
            // timestamps crea created_at y updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pausas');
    }
};