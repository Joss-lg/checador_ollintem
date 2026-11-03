<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// La hora sale de config/asistencia.php ("HH:MM:SS" → "HH:MM").
Schedule::command('asistencias:cerrar-automatico')
    ->dailyAt(substr(config('asistencia.hora_corte_auto', '18:01:00'), 0, 5));