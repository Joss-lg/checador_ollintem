<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pausa;
use App\Support\EstadoTurnoPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    private static function horaInicio(): string  { return config('asistencia.hora_inicio_jornada', '09:00:00'); }
    private static function horaFin(): string     { return Asistencia::horaFinJornada(); }
    private static function horaCorte(): string   { return config('asistencia.hora_corte_auto',     '18:01:00'); }

    /**
     * "18:00:00" → "6:00 p.m.", para que los mensajes sigan a config/asistencia.php.
     */
    private static function horaLegible(string $hora): string
    {
        $c = Carbon::createFromTimeString($hora);

        return $c->format('g:i') . ($c->hour < 12 ? ' a.m.' : ' p.m.');
    }

    /**
     * Hora de salida a guardar: la actual, o la hora fin oficial si ya pasó.
     */
    private static function horaSalidaTopada(): string
    {
        $finTurno = Carbon::today()->setTimeFromTimeString(self::horaFin());

        return now()->gt($finTurno) ? self::horaFin() : now()->format('H:i:s');
    }

    public function index()
    {
        Asistencia::registrarActividad(Auth::id());

        $asistencia = Asistencia::where('user_id', Auth::id())
            ->where('fecha', now()->toDateString())
            ->latest()
            ->first();

        $estado = 'inactivo';
        $horaEntrada = null;
        $horaSalida = null;
        $pausaInicio = null;
        $segundosPausaAcumulados = 0;

        // Las fechas van en formato ISO ("2026-11-03T09:00:00"): con espacio
        // en vez de "T", Safari (iPhone) no las entiende y los cronómetros
        // del panel se quedan en 00:00:00.
        if ($asistencia) {
            $horaEntrada = $asistencia->fecha . 'T' . $asistencia->hora_entrada;

            if ($asistencia->hora_salida) {
                // Jornada de hoy ya cerrada: se muestra como terminada y no
                // se permite registrar otra entrada el mismo día.
                $estado     = 'terminado';
                $horaSalida = $asistencia->fecha . 'T' . $asistencia->hora_salida;
            } else {
                $pausa = Pausa::where('asistencia_id', $asistencia->id)
                    ->whereNull('fin_pausa')
                    ->latest()
                    ->first();

                if ($pausa) {
                    $estado = 'pausado';
                    $pausaInicio = $asistencia->fecha . 'T' . $pausa->inicio_pausa;
                } else {
                    $estado = 'trabajando';
                }
            }

            // Pausas terminadas, con el mismo cálculo (y recorte) que el
            // historial; la pausa en curso la suma el JS en vivo.
            $segundosPausaAcumulados = $asistencia->tiempoPausasSegundos(soloCerradas: true);
        }

        return view('becario.dashboard', [
            'presenter'               => new EstadoTurnoPresenter($estado),
            'horaEntrada'             => $horaEntrada,
            'pausaInicio'             => $pausaInicio,
            'horaSalida'              => $horaSalida,
            'segundosPausaAcumulados' => $segundosPausaAcumulados,
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
                'El registro de entrada solo está permitido entre las ' . self::horaLegible(self::horaInicio()) . ' y las ' . self::horaLegible(self::horaFin()) . '.'
            );
        }

        return $this->conBloqueo(function () use ($horaActual) {
            $hoy = now()->toDateString();

            $jornadaHoy = Asistencia::where('user_id', Auth::id())
                ->where('fecha', $hoy)
                ->first();

            if ($jornadaHoy) {
                return back()->with('error', $jornadaHoy->hora_salida
                    ? 'Ya registraste tu jornada de hoy. Si necesitas reabrirla, contacta al administrador.'
                    : 'Ya cuentas con un registro de entrada activo para el día de hoy.');
            }

            // Jornadas olvidadas de días anteriores: se cierran a la hora fin
            // oficial, junto con sus pausas abiertas.
            Asistencia::where('user_id', Auth::id())
                ->whereNull('hora_salida')
                ->get()
                ->each->cerrarAutomaticamente();

            Asistencia::create([
                'user_id'      => Auth::id(),
                'hora_entrada' => $horaActual->format('H:i:s'),
                'fecha'        => $hoy,
            ]);

            return back()->with('success', 'Entrada registrada correctamente.');
        });
    }

    /**
     * Ejecuta una acción del becario con un candado por usuario, para que un
     * doble clic (dos peticiones casi simultáneas) no cree jornadas o pausas
     * duplicadas: la segunda petición se rechaza mientras la primera corre.
     */
    private function conBloqueo(callable $accion)
    {
        $candado = Cache::lock('asistencia:' . Auth::id(), 10);

        if (! $candado->get()) {
            return back()->with('error', 'Tu registro anterior todavía se está procesando. Intenta de nuevo.');
        }

        try {
            return $accion();
        } finally {
            $candado->release();
        }
    }

    public function registrarSalida()
    {
        if (!Auth::check()) return redirect('/login');

        $horaActual      = now();
        $inicioTurno     = Carbon::today()->setTimeFromTimeString(self::horaInicio());
        $finTurno        = Carbon::today()->setTimeFromTimeString(self::horaFin());
        $corteAutomatico = Carbon::today()->setTimeFromTimeString(self::horaCorte());

        // Fuera del horario permitido
        if ($horaActual->lt($inicioTurno) || $horaActual->gt($finTurno)) {
            // Si ya pasó el corte automático, cerrar la jornada a las 18:00
            if ($horaActual->gte($corteAutomatico)) {
                $abiertas = Asistencia::where('user_id', Auth::id())
                    ->whereNull('hora_salida')
                    ->get();

                // Aquí el becario sí está presente (acaba de presionar el
                // botón), así que se cierra a la hora fin oficial.
                $abiertas->each->cerrar(self::horaFin());

                if ($abiertas->isNotEmpty()) {
                    return back()->with(
                        'warning',
                        'Al no registrar salida dentro del horario permitido, tu turno fue cerrado automáticamente a las ' . self::horaLegible(self::horaFin()) . '.'
                    );
                }
            }

            return back()->with(
                'error',
                'El registro de salida debe realizarse entre las ' . self::horaLegible(self::horaInicio()) . ' y las ' . self::horaLegible(self::horaFin()) . '.'
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

        $asistencia->cerrar(self::horaSalidaTopada());

        return redirect()->route('dashboard')
            ->with('warning', 'Se registró tu salida automáticamente por inactividad.');
    }

    public function iniciarPausa(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $request->validate([
            'motivo' => ['required', 'string', 'in:Almuerzo,Personal'],
        ]);

        return $this->conBloqueo(function () use ($request) {
            // Solo la jornada abierta de HOY: no se abren pausas sobre jornadas
            // olvidadas de días anteriores.
            $asistencia = Asistencia::where('user_id', Auth::id())
                ->whereNull('hora_salida')
                ->where('fecha', now()->toDateString())
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
        });
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
        // Cada consulta del panel cuenta como "el becario sigue aquí"
        Asistencia::registrarActividad(Auth::id());

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
            $inicioPausa  = Carbon::parse($asistencia->fecha . ' ' . $pausaActiva->inicio_pausa);
            $minutosPausa = $inicioPausa->diffInMinutes(now());

            if ($minutosPausa >= 60) {
                $pausaExcedida = true;

                // Idempotente: solo actuar si la jornada sigue abierta
                // (el polling puede llamar aquí varias veces antes de que
                // el becario recargue la página)
                $asistencia->refresh();
                if (!$asistencia->hora_salida) {
                    $asistencia->cerrar(self::horaSalidaTopada());
                }
            }
        }

        return response()->json([
            'terminado'     => $pausaExcedida,
            'pausaExcedida' => $pausaExcedida,
        ]);
    }
}
