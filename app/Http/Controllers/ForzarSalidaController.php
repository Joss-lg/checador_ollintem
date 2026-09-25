<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Models\Asistencia;
use App\Models\Pausa;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * El administrador fuerza el registro de salida de un becario.
 * Es exactamente lo mismo que si el becario marcara su propia salida,
 * pero lo ejecuta el admin desde el panel.
 *
 * Si el becario tiene una pausa activa, se cierra primero para no
 * dejar datos colgados (mismo comportamiento que registrarSalidaInactividad).
 */
class ForzarSalidaController extends Controller
{
    use AuthorizesAdmin;

    public function forzar(Request $request, User $user)
    {
        $this->authorizeAdmin();

        abort_if($user->role !== 'becario', 403);

        $asistencia = Asistencia::where('user_id', $user->id)
            ->whereNull('hora_salida')
            ->where('fecha', today()->toDateString())
            ->first();

        if (! $asistencia) {
            return response()->json([
                'ok'      => false,
                'mensaje' => "{$user->name} no tiene una jornada activa hoy.",
            ], 422);
        }

        // Cerrar pausa activa si la hay (no dejar datos colgados)
        Pausa::where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->update(['fin_pausa' => now()->format('H:i:s')]);

        $asistencia->update([
            'hora_salida' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'ok'      => true,
            'mensaje' => "Salida registrada para {$user->name}.",
        ]);
    }
}
