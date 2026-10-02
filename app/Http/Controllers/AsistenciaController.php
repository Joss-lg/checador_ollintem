<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pausa;
use App\Support\EstadoTurnoPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    private static function horaInicio(): string  { return config('asistencia.hora_inicio_jornada', '09:00:00'); }
    private static function horaFin(): string     { return config('asistencia.hora_fin_jornada',    '18:00:00'); }
    private static function horaCorte(): string   { return config('asistencia.hora_corte_auto',     '18:01:00'); }

    public function index()
    {
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->latest()
            ->first();

        $estado = 'inactivo';
        $horaEntrada = null;
        $horaSalida = null;
        $pausaInicio = null;
        $segundosPausaAcumulados = 0;

        if ($asistencia && !$asistencia->hora_salida) {
            $horaEntrada = $asistencia->fecha . ' ' . $asistencia->hora_entrada;

            $pausa = Pausa::where('asistencia_id', $asistencia->id)
                ->whereNull('fin_pausa')
                ->latest()
                ->first();

            if ($pausa) {
                $estado = 'pausado';
                $pausaInicio = $asistencia->fecha . ' ' . $pausa->inicio_pausa;
            } else {
                $estado = 'trabajando';
            }

            // Sumar pausas terminadas de la jornada activa
            $pausas = Pausa::where('asistencia_id', $asistencia->id)
                ->whereNotNull('fin_pausa')
                ->get();

            foreach ($pausas as $p) {
                $inicio = Carbon::parse($p->inicio_pausa);
                $fin = Carbon::parse($p->fin_pausa);
                $segundosPausaAcumulados += $inicio->diffInSeconds($fin);
            }
        }

        return view('becario.dashboard', [
            'presenter'               => new EstadoTurnoPresenter($estado ?? null),
            'horaEntrada'             => $horaEntrada ?? null,
            'pausaInicio'             => $pausaInicio ?? null,
            'horaSalida'              => $horaSalida ?? null,
            'segundosPausaAcumulados' => $segundosPausaAcumulados ?? 0,
            'userId'                  => Auth::id(),
        ]);
    }

    public function registrarEntrada()
    {
        if (!Auth::check()) return redirect('/login');

        $horaActual  = now();
        $inicioTurno = Carbon::today()->setTimeFromTimeString(self::horaInicio());
        $finTurno    = Carbon::today()->setTimeFromTimeString(self::horaFin());

        if ($horaActual->lt($inicioTurno) || $horaActual->gt($finTurno)) {
            return back()->with(
                'error',
                'El registro de entrada solo está permitido entre las 9:00 a.m. y las 6:00 p.m.'
            );
        }

        $asistenciaActiva = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->first();

        if ($asistenciaActiva) {
            $hoy = now()->toDateString();
            
            if ($asistenciaActiva->fecha === $hoy) {
                return back()->with('error', 'Ya cuentas con un registro de entrada activo para el día de hoy.');
            }

            $asistenciaActiva->update(['hora_salida' => self::horaFin()]);
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
        $inicioTurno     = Carbon::today()->setTimeFromTimeString(self::horaInicio());
        $finTurno        = Carbon::today()->setTimeFromTimeString(self::horaFin());
        $corteAutomatico = Carbon::today()->setTimeFromTimeString(self::horaCorte());

        if ($horaActual->gte($corteAutomatico)) {
            $actualizados = Asistencia::where('user_id', Auth::id())
                ->whereNull('hora_salida')
                ->update(['hora_salida' => self::horaFin()]);

            if ($actualizados > 0) {
                return back()->with(
                    'warning',
                    'Al no registrar salida dentro del horario permitido, tu turno fue cerrado automáticamente a las 6:00 p.m.'
                );
            }
        }

        if ($horaActual->lt($inicioTurno) || $horaActual->gt($finTurno)) {
            return back()->with(
                'error',
                'El registro de salida debe realizarse entre las 9:00 a.m. y las 6:00 p.m.'
            );
        }

        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->where('fecha', now()->toDateString())
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No se encontró un registro de entrada activo para el día de hoy.');
        }

        $pausaActiva = Pausa::where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->exists();

        if ($pausaActiva) {
            return back()->with('error', 'Primero debes finalizar tu pausa activa antes de registrar salida.');
        }

        $asistencia->update([
            'hora_salida' => $horaActual->format('H:i:s')
        ]);

        return back()->with('success', 'Salida registrada correctamente.');
    }

    public function registrarSalidaInactividad()
    {
        if (!Auth::check()) return redirect('/login');

        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->where('fecha', now()->toDateString())
            ->first();

        if (!$asistencia) {
            return redirect()->route('dashboard');
        }

        Pausa::where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->update(['fin_pausa' => now()->format('H:i:s')]);

        $horaActual  = now();
        $corteCarbon = Carbon::today()->setTimeFromTimeString(self::horaFin());
        $horaSalida  = $horaActual->gt($corteCarbon)
            ? self::horaFin()
            : $horaActual->format('H:i:s');

        $asistencia->update(['hora_salida' => $horaSalida]);

        return redirect()->route('dashboard')
            ->with('warning', 'Se registró tu salida automáticamente por inactividad.');
    }

    /**
     * Registra la salida automática cuando el becario cierra la pestaña o el navegador.
     * Soporta sesión activa o user_id recibido vía sendBeacon.
     */
    public function registrarSalidaInvoluntaria(Request $request)
    {
        $userId = Auth::id() ?? $request->input('user_id');

        if (!$userId) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $asistencia = Asistencia::where('user_id', $userId)
            ->whereNull('hora_salida')
            ->where('fecha', now()->toDateString())
            ->first();

        if (!$asistencia) {
            return response()->json(['status' => 'no_active_session'], 200);
        }

        // Si hay una pausa activa la cerramos
        Pausa::where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->update(['fin_pausa' => now()->format('H:i:s')]);

        $horaActual  = now();
        $corteCarbon = Carbon::today()->setTimeFromTimeString(self::horaFin());

        $horaSalida = $horaActual->gt($corteCarbon)
            ? self::horaFin()
            : $horaActual->format('H:i:s');

        $asistencia->update(['hora_salida' => $horaSalida]);

        return response()->json(['status' => 'success', 'hora_salida' => $horaSalida]);
    }

    public function iniciarPausa(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $asistencia = Asistencia::where('user_id', Auth::id())
            ->whereNull('hora_salida')
            ->latest('fecha')
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No existe una jornada activa para iniciar pausa.');
        }

        $pausaActiva = Pausa::where('user_id', Auth::id())
            ->where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->exists();

        if ($pausaActiva) {
            return back()->with('error', 'Ya cuentas con una pausa activa.');
        }

        Pausa::create([
            'user_id'       => Auth::id(),
            'asistencia_id' => $asistencia->id,
            'inicio_pausa'  => now()->format('H:i:s'),
            'motivo'        => $request->motivo,
            'fecha'         => now()->toDateString(),
        ]);

        return back()->with('success', 'Pausa iniciada correctamente.');
    }

    public function finalizarPausa()
    {
        if (!Auth::check()) return redirect('/login');

        $pausa = Pausa::where('user_id', Auth::id())
            ->whereNull('fin_pausa')
            ->latest()
            ->first();

        if (!$pausa) {
            return back()->with('error', 'No se encontró ninguna pausa activa.');
        }

        $pausa->update([
            'fin_pausa' => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Pausa finalizada correctamente.');
    }

    public function estadoJornada()
    {
        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', today()->toDateString())
            ->latest()
            ->first();

        if (!$asistencia) {
            return response()->json(['terminado' => false, 'pausaExcedida' => false]);
        }

        if ($asistencia->hora_salida) {
            return response()->json(['terminado' => true, 'pausaExcedida' => false]);
        }

        $pausaActiva = Pausa::where('asistencia_id', $asistencia->id)
            ->whereNull('fin_pausa')
            ->latest()
            ->first();

        $pausaExcedida = false;

        if ($pausaActiva) {
            $minutosPausa = Carbon::parse($pausaActiva->inicio_pausa)->diffInMinutes(now());

            if ($minutosPausa >= 60) {
                $pausaExcedida = true;

                // Idempotente: solo actuar si la jornada sigue abierta
                // (el polling puede llamar aquí varias veces antes de que
                // el becario recargue la página)
                $asistencia->refresh();
                if (!$asistencia->hora_salida) {
                    $pausaActiva->update(['fin_pausa' => now()->format('H:i:s')]);

                    $horaActual  = now();
                    $corteCarbon = Carbon::today()->setTimeFromTimeString(self::horaFin());
                    $horaSalida  = $horaActual->gt($corteCarbon)
                        ? self::horaFin()
                        : $horaActual->format('H:i:s');

                    $asistencia->update(['hora_salida' => $horaSalida]);
                }
            }
        }

        return response()->json([
            'terminado'     => $pausaExcedida,
            'pausaExcedida' => $pausaExcedida,
        ]);
    }
}