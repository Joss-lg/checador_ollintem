<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    use AuthorizesAdmin;

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = Asistencia::with(['user', 'pausas'])
            ->buscarBecario($request->input('search'))
            ->filtrarPorSemana($request->input('semana'))
            ->filtrarPorMes($request->input('mes'));

        // Ordenamiento dinámico
        switch ($request->get('order')) {
            case 'az':
                $query->join('users', 'users.id', '=', 'asistencias.user_id')
                      ->orderBy('users.name', 'asc')
                      ->select('asistencias.*');
                break;
            case 'za':
                $query->join('users', 'users.id', '=', 'asistencias.user_id')
                      ->orderBy('users.name', 'desc')
                      ->select('asistencias.*');
                break;
            case 'oldest':
                $query->orderBy('fecha', 'asc');
                break;
            default:
                $query->orderBy('fecha', 'desc');
                break;
        }

        // Obtener meses disponibles para el selector de la vista
        $meses = Asistencia::selectRaw('MONTH(fecha) as numero_mes')
            ->distinct()
            ->orderBy('numero_mes')
            ->get();

        $asistencias = $query->paginate(15)->withQueryString();

        return view('admin.historial.index', compact('asistencias', 'meses'));
    }

    public function editarHoras(Request $request, Asistencia $asistencia)
    {
        $this->authorizeAdmin();

        $request->validate([
            'hora_entrada' => ['required', 'date_format:H:i,H:i:s'],
            'hora_salida'  => ['nullable', 'date_format:H:i,H:i:s', 'after:hora_entrada'],
            'motivo'       => ['required', 'string', 'max:255'],
        ]);

        $asistencia->update([
            'hora_entrada' => $request->hora_entrada,
            'hora_salida'  => $request->hora_salida ?: null,
        ]);

        return back()->with('success',
            "Jornada del {$asistencia->fecha} corregida. Motivo: {$request->motivo}"
        );
    }
}