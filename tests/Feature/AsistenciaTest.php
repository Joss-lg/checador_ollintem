<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\CorreccionAsistencia;
use App\Models\Pausa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Flujos principales del checador: entrada, pausas, salida, cierre
 * automático, dashboard del admin, corrección de horas y borrado de usuarios.
 *
 * Usan SQLite en memoria (phpunit.xml). Si tu PHP no tiene pdo_sqlite
 * activado en php.ini, córrelas con (artisan test no pasa el -d a PHPUnit):
 *     php -d extension=pdo_sqlite vendor/bin/phpunit
 */
class AsistenciaTest extends TestCase
{
    use RefreshDatabase;

    private const HOY = '2026-09-14';

    protected function setUp(): void
    {
        parent::setUp();
        $this->enHora('10:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function enHora(string $hora, string $fecha = self::HOY): void
    {
        Carbon::setTestNow(Carbon::parse("{$fecha} {$hora}"));
    }

    private function becario(array $datos = []): User
    {
        return User::factory()->create(array_merge(['role' => 'becario'], $datos));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function jornada(User $user, array $datos = []): Asistencia
    {
        return Asistencia::create(array_merge([
            'user_id'      => $user->id,
            'fecha'        => self::HOY,
            'hora_entrada' => '09:00:00',
        ], $datos));
    }

    // ── Entrada ──────────────────────────────────────────────────────────

    public function test_registra_entrada_dentro_del_horario(): void
    {
        $becario = $this->becario();

        $this->actingAs($becario)->post(route('entrada'))->assertSessionHas('success');

        $this->assertDatabaseHas('asistencias', [
            'user_id'      => $becario->id,
            'fecha'        => self::HOY,
            'hora_entrada' => '10:00:00',
        ]);
    }

    public function test_no_permite_dos_entradas_el_mismo_dia(): void
    {
        $becario = $this->becario();

        $this->actingAs($becario)->post(route('entrada'));
        $this->actingAs($becario)->post(route('entrada'))->assertSessionHas('error');

        $this->assertSame(1, Asistencia::where('user_id', $becario->id)->count());
    }

    public function test_no_permite_entrada_fuera_del_horario(): void
    {
        $this->enHora('08:00:00');
        $becario = $this->becario();

        $this->actingAs($becario)->post(route('entrada'))->assertSessionHas('error');

        $this->assertSame(0, Asistencia::count());
    }

    public function test_la_entrada_cierra_jornadas_olvidadas_de_dias_anteriores(): void
    {
        $becario = $this->becario();
        $vieja   = $this->jornada($becario, ['fecha' => '2026-09-11']);

        $this->actingAs($becario)->post(route('entrada'));

        $this->assertSame('18:00:00', $vieja->fresh()->hora_salida);
    }

    // ── Pausas y salida ──────────────────────────────────────────────────

    public function test_pausa_requiere_un_motivo_valido(): void
    {
        $becario = $this->becario();
        $this->jornada($becario);

        $this->actingAs($becario)->post(route('pausa.iniciar'), ['motivo' => 'Otro'])
            ->assertSessionHasErrors('motivo');

        $this->assertSame(0, Pausa::count());
    }

    public function test_inicia_y_finaliza_pausa(): void
    {
        $becario = $this->becario();
        $this->jornada($becario);

        $this->actingAs($becario)->post(route('pausa.iniciar'), ['motivo' => 'Almuerzo'])
            ->assertSessionHas('success');

        $this->enHora('10:30:00');
        $this->actingAs($becario)->post(route('pausa.finalizar'))->assertSessionHas('success');

        $pausa = Pausa::first();
        $this->assertSame('10:00:00', $pausa->inicio_pausa);
        $this->assertSame('10:30:00', $pausa->fin_pausa);
    }

    public function test_no_permite_salida_con_pausa_activa(): void
    {
        $becario = $this->becario();
        $jornada = $this->jornada($becario);
        Pausa::create(['user_id' => $becario->id, 'asistencia_id' => $jornada->id,
                       'inicio_pausa' => '09:30:00', 'motivo' => 'Personal', 'fecha' => self::HOY]);

        $this->actingAs($becario)->post(route('salida'))->assertSessionHas('error');

        $this->assertNull($jornada->fresh()->hora_salida);
    }

    public function test_registra_salida(): void
    {
        $becario = $this->becario();
        $jornada = $this->jornada($becario);

        $this->enHora('14:15:00');
        $this->actingAs($becario)->post(route('salida'))->assertSessionHas('success');

        $this->assertSame('14:15:00', $jornada->fresh()->hora_salida);
    }

    // ── Cierre automático por última actividad ───────────────────────────

    public function test_cierre_automatico_usa_la_ultima_actividad_y_cierra_pausas(): void
    {
        $becario = $this->becario();
        $jornada = $this->jornada($becario);
        Pausa::create(['user_id' => $becario->id, 'asistencia_id' => $jornada->id,
                       'inicio_pausa' => '13:00:00', 'motivo' => 'Almuerzo', 'fecha' => self::HOY]);

        $this->enHora('13:05:20');
        Asistencia::registrarActividad($becario->id);

        $this->enHora('18:01:00');
        Asistencia::cerrarAbiertas(self::HOY);

        $jornada->refresh();
        $this->assertSame('13:05:20', $jornada->hora_salida);
        $this->assertSame('13:05:20', $jornada->pausas()->first()->fin_pausa);
    }

    public function test_cierre_automatico_dentro_del_margen_usa_la_hora_fin(): void
    {
        $becario = $this->becario();
        $jornada = $this->jornada($becario);

        $this->enHora('17:55:00');
        Asistencia::registrarActividad($becario->id);

        $this->enHora('18:01:00');
        Asistencia::cerrarAbiertas(self::HOY);

        $this->assertSame('18:00:00', $jornada->fresh()->hora_salida);
    }

    public function test_cierre_automatico_sin_actividad_usa_la_hora_fin(): void
    {
        $becario = $this->becario();
        $jornada = $this->jornada($becario);
        Cache::flush();

        $this->enHora('18:01:00');
        Asistencia::cerrarAbiertas(self::HOY);

        $this->assertSame('18:00:00', $jornada->fresh()->hora_salida);
    }

    // ── Cálculo de tiempos ───────────────────────────────────────────────

    public function test_pausas_fuera_del_horario_no_se_restan(): void
    {
        $becario = $this->becario();
        $jornada = $this->jornada($becario, ['hora_salida' => '12:00:00']);
        foreach ([['11:30:00', '12:30:00'], ['13:00:00', '14:00:00']] as [$inicio, $fin]) {
            Pausa::create(['user_id' => $becario->id, 'asistencia_id' => $jornada->id,
                           'inicio_pausa' => $inicio, 'fin_pausa' => $fin, 'motivo' => 'Personal', 'fecha' => self::HOY]);
        }

        $jornada->load('pausas');

        $this->assertSame(30 * 60, $jornada->tiempoPausasSegundos());
        $this->assertSame((2 * 60 + 30) * 60, $jornada->tiempoTrabajado());
    }

    // ── Admin ────────────────────────────────────────────────────────────

    public function test_dashboard_admin_soporta_nombres_con_acento_y_sin_conexion(): void
    {
        $becario = $this->becario(['name' => 'Ángel Núñez']);
        $this->jornada($becario);

        $this->enHora('10:00:00');
        Asistencia::registrarActividad($becario->id);

        $this->enHora('11:00:00');
        $respuesta = $this->actingAs($this->admin())->getJson(route('admin.tiempos'))->assertOk();

        $fila = collect($respuesta->json())->firstWhere('user_id', $becario->id);
        $this->assertSame('Á', $fila['user_inicial']);
        $this->assertTrue($fila['sin_conexion']);
        // Tiempo calculado hasta la última actividad (10:00), no hasta ahora (11:00)
        $this->assertSame(60 * 60, $fila['trabajado_segundos']);
    }

    public function test_becario_no_entra_a_rutas_de_admin(): void
    {
        $this->actingAs($this->becario())->get(route('admin.historial'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_corregir_horas_guarda_la_bitacora(): void
    {
        $admin   = $this->admin();
        $jornada = $this->jornada($this->becario(), ['hora_salida' => '18:00:00']);

        $this->actingAs($admin)->patch(route('admin.historial.editar-horas', $jornada), [
            'hora_entrada' => '09:00',
            'hora_salida'  => '14:00',
            'motivo'       => 'Salió temprano con permiso',
        ])->assertSessionHas('success');

        $this->assertSame('14:00', substr($jornada->fresh()->hora_salida, 0, 5));
        $correccion = CorreccionAsistencia::first();
        $this->assertSame($admin->id, $correccion->corregido_por);
        $this->assertSame('18:00:00', $correccion->salida_antes);
        $this->assertSame('Salió temprano con permiso', $correccion->motivo);
    }

    public function test_eliminar_usuario_con_pausas(): void
    {
        $admin   = $this->admin();
        $becario = $this->becario();
        $jornada = $this->jornada($becario);
        Pausa::create(['user_id' => $becario->id, 'asistencia_id' => $jornada->id,
                       'inicio_pausa' => '09:30:00', 'fin_pausa' => '09:45:00', 'motivo' => 'Personal', 'fecha' => self::HOY]);

        $this->actingAs($admin)->delete(route('users.delete', $becario->id))->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $becario->id]);
        $this->assertSame(0, Asistencia::count());
        $this->assertSame(0, Pausa::count());
    }
}
