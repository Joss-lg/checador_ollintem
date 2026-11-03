<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Registro de la última corrección manual de una jornada desde el
 * historial (antes el motivo solo se mostraba en el mensaje y se perdía).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->string('motivo_correccion')->nullable()->after('fecha');
            $table->foreignId('corregido_por')->nullable()->after('motivo_correccion')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('corregido_at')->nullable()->after('corregido_por');
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropConstrainedForeignId('corregido_por');
            $table->dropColumn(['motivo_correccion', 'corregido_at']);
        });
    }
};
