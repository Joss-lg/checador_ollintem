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
        
        // Simplemente pon la columna aquí, SIN el ->after('user_id')
        $table->foreignId('asistencia_id')->nullable()->constrained(); 
        
        $table->time('inicio_pausa')->nullable();
        $table->time('fin_pausa')->nullable();
        $table->string('motivo');
        $table->date('fecha');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('pausas');
    }
};