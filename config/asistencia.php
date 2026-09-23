<?php

// Horarios oficiales de la jornada laboral. Un solo lugar para todo el
// sistema: AsistenciaController, el comando de cierre automático y el
// scheduler leen de aquí en vez de tener la hora repetida en 3 archivos.

return [

    // Hora a partir de la cual se puede registrar entrada.
    'hora_inicio_jornada' => env('ASISTENCIA_HORA_INICIO', '09:00:00'),

    // Hora límite para registrar entrada/salida dentro del horario normal.
    'hora_fin_jornada' => env('ASISTENCIA_HORA_FIN', '18:00:00'),

    // Hora en la que se cierra automáticamente cualquier jornada que
    // haya quedado abierta (comando asistencias:cerrar-automatico).
    'hora_corte_auto' => env('ASISTENCIA_HORA_CORTE_AUTO', '18:01:00'),

];
