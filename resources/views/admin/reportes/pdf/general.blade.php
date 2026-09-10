<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General - OllinCheck</title>
    <style>
        @include('admin.reportes.pdf.partials.styles')
    </style>
</head>
<body>

<div class="header">
    <h1>OLLIN CHECK</h1>
    <p>Reporte general de asistencia</p>
</div>

<div class="section-title">
    Resumen general
</div>

<table class="summary-table">
    <tr>
        <td>
            <div class="summary-title">Jornadas</div>
            <div class="summary-value">{{ $resumen['jornadas'] }}</div>
        </td>
        <td>
            <div class="summary-title">Tiempo trabajado</div>
            <div class="summary-value">{{ $resumen['horas_trabajadas'] }}</div>
        </td>
        <td>
            <div class="summary-title">Pausas</div>
            <div class="summary-value">{{ $resumen['tiempo_pausa'] }}</div>
        </td>
    </tr>
</table>

<div class="section-title">
    Registro de asistencias
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Becario</th>
            <th>Fecha</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Pausas</th>
            <th>Trabajo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asistencias as $asistencia)
        <tr>
            <td>{{ $asistencia->user->name }}</td>
            <td>{{ $asistencia->fecha }}</td>
            <td>{{ $asistencia->hora_entrada ?? '--' }}</td>
            <td>{{ $asistencia->hora_salida ?? '--' }}</td>
            <td>{{ $asistencia->tiempoPausas() }}</td>
            <td>
                {{ 
                    $asistencia->formatoTiempo(
                        $asistencia->tiempoTrabajado()
                    )
                }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    OllinCheck | Generado: {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>