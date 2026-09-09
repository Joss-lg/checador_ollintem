<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    // Ventanas de tiempo estrictas
    private const HORA_ENTRADA_INICIO = '09:00:00';
    private const HORA_ENTRADA_FIN    = '09:30:00';
    private const HORA_SALIDA_INICIO  = '17:30:00';
    private const HORA_SALIDA_FIN     = '18:00:00';
    private const HORA_CORTE_AUTO     = '18:01:00';

    public function registrarEntrada()
    {
        if (!Auth::check()) return redirect('/login');

        $horaActual    = now();
        $inicioEntrada = Carbon::today()->setTimeFromTimeString(self::HORA_ENTRADA_INICIO);
        $finEntrada    = Carbon::today()->setTimeFromTimeString(self::HORA_ENTRADA_FIN);

        // Restricción estricta: Solo entre 9:00 am y 9:30 am
        if ($horaActual->lt($inicioEntrada) || $horaActual->gt($finEntrada)) {
            return back()->with(
                'error',
                'El registro de entrada solo está permitido estrictamente entre las 9:00 a.m. y las 9:30 a.m.'
            );
        }

        // Evitar doble registro el mismo día
        $existente = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->first();

        if ($existente) {
            return back()->with(
                'error',
                'Ya cuentas con un registro de entrada correspondiente al día de hoy.'
            );
        }

        Asistencia::create([
            'user_id'      => Auth::id(),
            'hora_entrada' => $horaActual->format('H:i:s'),
            'fecha'        => now()->toDateString(),
        ]);

        return back()->with('success', 'Entrada registrada correctamente.');
    }

    public function registrarSalida()
    {
        if (!Auth::check()) return redirect('/login');

        $horaActual      = now();
        $inicioSalida    = Carbon::today()->setTimeFromTimeString(self::HORA_SALIDA_INICIO);
        $finSalida       = Carbon::today()->setTimeFromTimeString(self::HORA_SALIDA_FIN);
        $corteAutomatico = Carbon::today()->setTimeFromTimeString(self::HORA_CORTE_AUTO);

        // 1. Corte automático: Si son las 6:01 p.m. o más tarde, se cierra automáticamente
        if ($horaActual->gte($corteAutomatico)) {
            $actualizados = Asistencia::where('user_id', Auth::id())
                ->whereNull('hora_salida')
                ->update(['hora_salida' => self::HORA_SALIDA_FIN]);

            if ($actualizados > 0) {
                return back()->with(
                    'warning',
                    'Ha pasado el límite de las 6:01 p.m. Tu turno ha sido cerrado automáticamente a las 18:00 hrs.'
                );
            }
        }

        // 2. Restricción: No se permite registrar salida antes de las 5:30 p.m.
        if ($horaActual->lt($inicioSalida)) {
            return back()->with(
                'error',
                'Aún no es hora de salida. El horario permitido para registrar salida es de 5:30 p.m. a 6:00 p.m.'
            );
        }

        // Buscamos el registro activo del usuario para el día de hoy
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->where('fecha', now()->toDateString())
            ->first();

        if (!$asistencia) {
            return back()->with(
                'error',
                'No se encontró un registro de entrada activo para el día de hoy.'
            );
        }

        // 3. Si intenta registrar entre las 6:00 p.m. y las 6:01 p.m., se topa exactamente a las 18:00:00
        $horaSalida = $horaActual->gt($finSalida) 
            ? self::HORA_SALIDA_FIN 
            : $horaActual->format('H:i:s');

        $asistencia->update([
            'hora_salida' => $horaSalida
        ]);

        return back()->with('success', 'Salida registrada correctamente.');
    }
}