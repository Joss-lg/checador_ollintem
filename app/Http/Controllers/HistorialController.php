<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    use AuthorizesAdmin;

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $filtros = $request->only(['search', 'semana', 'mes']);

        $query = Asistencia::with(['user', 'pausas'])
            ->filtrosHistorial($filtros)
            ->ordenar($request->input('order'));

        // Estadísticas sobre TODOS los resultados filtrados, no solo sobre
        // los 15 de la página actual.
        $base  = Asistencia::query()->filtrosHistorial($filtros);
        $stats = [
            'registros'   => (clone $base)->count(),
            'becarios'    => (clone $base)->distinct()->count('user_id'),
            'finalizados' => (clone $base)->whereNotNull('hora_salida')->count(),
            'en_curso'    => (clone $base)->whereNull('hora_salida')->count(),
        ];

        // Meses disponibles para el selector, como "YYYY-MM" (más reciente
        // primero) para no mezclar el mismo mes de años distintos. Se arma en
        // PHP en lugar de MONTH() para no depender de funciones de MySQL.
        $meses = Asistencia::query()
            ->distinct()
            ->pluck('fecha')
            ->map(fn ($fecha) => substr($fecha, 0, 7))
            ->unique()
            ->sortDesc()
            ->values();

        $asistencias = $query->paginate(15)->withQueryString();

        return view('admin.historial.index', compact('asistencias', 'meses', 'stats'));
    }

    public function editarHoras(Request $request, Asistencia $asistencia)
    {
        $this->authorizeAdmin();

        $request->validate([
            'hora_entrada' => ['required', 'date_format:H:i,H:i:s'],
            'hora_salida'  => ['nullable', 'date_format:H:i,H:i:s', 'after:hora_entrada'],
            'motivo'       => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $asistencia) {
            // Bitácora: cada corrección queda guardada con las horas de antes
            // y de después (las columnas de abajo solo guardan la última).
            $asistencia->correcciones()->create([
                'corregido_por'   => Auth::id(),
                'entrada_antes'   => $asistencia->hora_entrada,
                'salida_antes'    => $asistencia->hora_salida,
                'entrada_despues' => $request->hora_entrada,
                'salida_despues'  => $request->hora_salida ?: null,
                'motivo'          => $request->motivo,
            ]);

            // Se guarda quién corrigió, cuándo y por qué, para tener registro.
            $asistencia->update([
                'hora_entrada'      => $request->hora_entrada,
                'hora_salida'       => $request->hora_salida ?: null,
                'motivo_correccion' => $request->motivo,
                'corregido_por'     => Auth::id(),
                'corregido_at'      => now(),
            ]);
        });

        return back()->with('success',
            "Jornada del {$asistencia->fecha} corregida. Motivo: {$request->motivo}"
        );
    }
}