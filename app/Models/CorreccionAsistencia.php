<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Una corrección manual de horas hecha por un admin desde el historial.
 */
class CorreccionAsistencia extends Model
{
    protected $table = 'correcciones_asistencia';

    protected $fillable = [
        'asistencia_id',
        'corregido_por',
        'entrada_antes',
        'salida_antes',
        'entrada_despues',
        'salida_despues',
        'motivo',
    ];

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'corregido_por');
    }
}
