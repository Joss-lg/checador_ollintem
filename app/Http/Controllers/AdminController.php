<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use AuthorizesAdmin;

    public function index()
    {
        $this->authorizeAdmin();

        $hoy = now()->toDateString();

        $totalBecarios = User::where('role', 'becario')->count();

        $datosReporte = [
            ['Total Becarios', $totalBecarios . ' registrados'],
            [
                'Fecha del Reporte',
                Carbon::parse($hoy)->translatedFormat('d \d\e F \d\e Y'),
            ],
        ];

        return view('admin.dashboard', compact('datosReporte'));
    }

    public function storeBecario(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            // El correo se genera del nombre: si choca, lo más probable es
            // que ya exista otro becario con el mismo nombre.
            'email.unique'   => "Ya existe un usuario con el correo {$request->email}. Agrega el segundo apellido u otra inicial al nombre.",
            'email.required' => 'Escribe el nombre del becario para generar su correo.',
            'email.email'    => 'El correo generado no es válido. Revisa que el nombre solo tenga letras y espacios.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'becario',
        ]);

        return back()->with('success', 'Becario registrado correctamente.');
    }

    public function tiempos()
    {
        $this->authorizeAdmin();

        $hoy = today()->toDateString();

        $becarios = User::where('role', 'becario')
            ->with(['asistencias' => function ($q) use ($hoy) {
                $q->where('fecha', $hoy)
                  ->with('pausas')
                  ->latest();
            }])
            ->orderBy('name')
            ->get();

        $data = $becarios->map(function ($user) use ($hoy) {

            /** @var Asistencia|null $a */
            $a = $user->asistencias->first();

            // Becario sin marcar entrada hoy todavía
            if (!$a) {
                return [
                    'id'                  => null,
                    'user_id'             => $user->id,
                    'user_name'           => $user->name,
                    'user_inicial'        => mb_strtoupper(mb_substr($user->name, 0, 1)),
                    'fecha'               => Carbon::parse($hoy)->format('d/m/Y'),
                    'hora_entrada'        => '--:--',
                    'hora_salida'         => '---',
                    'pausas_segundos'     => 0,
                    'trabajado_segundos'  => 0,
                    'en_pausa'            => false,
                    'turno_terminado'     => false,
                    'sin_registro'        => true,
                    'estado'              => ['texto' => 'Sin registrar', 'clase' => 'badge-sin-registrar', 'style' => ''],
                ];
            }

            $enPausa = $a->tienePausaActiva();
            $turnoTerminado = (bool) $a->hora_salida;

            if ($turnoTerminado) {
                $estado = ['texto' => 'Turno terminado', 'clase' => 'badge-terminado', 'style' => ''];
            } elseif ($enPausa) {
                $estado = ['texto' => 'En descanso',     'clase' => 'badge-descanso',  'style' => ''];
            } else {
                $estado = ['texto' => 'Activo',          'clase' => 'badge-activo',    'style' => ''];
            }

            return [
                'id'                  => $a->id,
                'user_id'             => $user->id,
                'user_name'           => $user->name,
                'user_inicial'        => mb_strtoupper(mb_substr($user->name, 0, 1)),
                'fecha'               => Carbon::parse($a->fecha)->format('d/m/Y'),
                'hora_entrada'        => $a->hora_entrada ? Carbon::parse($a->hora_entrada)->format('h:i A') : '--:--',
                'hora_salida'         => $a->hora_salida ? Carbon::parse($a->hora_salida)->format('h:i A') : '---',
                'pausas_segundos'     => $a->tiempoPausasSegundos(),
                'trabajado_segundos'  => $a->tiempoTrabajado(),
                'en_pausa'            => $enPausa,
                'turno_terminado'     => $turnoTerminado,
                'sin_registro'        => false,
                'estado'              => $estado,
            ];
        });

        return response()->json($data->values());
    }
}