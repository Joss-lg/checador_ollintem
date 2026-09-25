<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ForzarSalidaController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Reportes\ExcelController;
use App\Http\Controllers\Reportes\PdfController;
use App\Http\Controllers\Reportes\ReporteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// =========================================================================
// MURALLA DIGITAL - APARTADO ADMIN
// =========================================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/asistencias-tiempo', [AdminController::class, 'tiempos'])->name('admin.tiempos');
    Route::get('/admin/historial', [HistorialController::class, 'index'])->name('admin.historial');
    Route::patch('/admin/historial/{asistencia}/editar-horas', [HistorialController::class, 'editarHoras'])->name('admin.historial.editar-horas');
    Route::get('/admin/historial/reporte/{user}', [ReporteController::class, 'show'])->name('admin.historial.reporte');
    Route::get('/admin/historial/{asistencia}', [HistorialController::class, 'show'])->name('admin.historial.show');
    Route::get('/admin/reportes/{user}/excel', [ExcelController::class, 'reporteBecario'])->name('admin.reportes.excel');
    Route::get('/admin/reportes/excel/general', [ExcelController::class, 'historialGeneral'])->name('admin.reportes.general.excel');
    Route::get('/admin/reportes/pdf/general', [PdfController::class, 'general'])->name('admin.reportes.pdf.general');
    Route::get('/admin/reportes/pdf/becario/{user}', [PdfController::class, 'becario'])->name('admin.reportes.pdf.becario');

    // ── Forzar salida de un becario ──────────────────────────────────────
    Route::post('/admin/forzar-salida/{user}', [ForzarSalidaController::class, 'forzar'])->name('admin.forzar-salida');

    // [BLOQUEO DE SEGURIDAD]
    Route::put('/admin/user/{id}', [HomeController::class, 'update'])->name('users.update');
    Route::post('/admin/becarios/store', [AdminController::class, 'storeBecario'])->name('admin.becarios.store');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/user/store', [HomeController::class, 'storeUser'])->name('users.store');
    Route::post('/home/user/toggle/{id}', [HomeController::class, 'toggleAdmin'])->name('users.toggle');
    Route::delete('/home/user/{id}', [HomeController::class, 'deleteUser'])->name('users.delete');
});
// =========================================================================


Route::middleware(['auth', 'role:becario'])->group(function () {
    Route::get('/dashboard', [AsistenciaController::class, 'index'])->name('dashboard');
    Route::post('/entrada', [AsistenciaController::class, 'registrarEntrada'])->name('entrada');
    Route::post('/salida', [AsistenciaController::class, 'registrarSalida'])->name('salida');
    Route::post('/pausa/iniciar', [AsistenciaController::class, 'iniciarPausa'])->name('pausa.iniciar');
    Route::post('/pausa/finalizar', [AsistenciaController::class, 'finalizarPausa'])->name('pausa.finalizar');
    Route::post('/salida/inactividad', [AsistenciaController::class, 'registrarSalidaInactividad'])->name('salida.inactividad');
});

Route::middleware('auth')->get('/api/rol-actual', function () {
    return response()->json(['role' => Auth::user()->role]);
})->name('api.rol-actual');
