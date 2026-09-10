<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Reporte General - OllinCheck</title>


    <style>
        <?php echo $__env->make('admin.reportes.pdf.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </style>

</head>


<body>


<div class="header">

    <h1>OLLIN CHECK</h1>

    <p>
        Reporte general de asistencia
    </p>

</div>



<div class="section-title">

    Resumen general

</div>



<table class="summary-table">

    <tr>

        <td>

            <div class="summary-title">
                Jornadas
            </div>

            <div class="summary-value">
                <?php echo e($resumen['jornadas']); ?>

            </div>

        </td>


        <td>

            <div class="summary-title">
                Tiempo trabajado
            </div>

            <div class="summary-value">
                <?php echo e($resumen['horas_trabajadas']); ?>

            </div>

        </td>


        <td>

            <div class="summary-title">
                Pausas
            </div>

            <div class="summary-value">
                <?php echo e($resumen['tiempo_pausa']); ?>

            </div>

        </td>


        <td>

            <div class="summary-title">
                Horas extra
            </div>

            <div class="summary-value">
                <?php echo e($resumen['horas_extra']); ?>

            </div>

        </td>


    </tr>

</table>




<div class="section-title">

    Registro de asistencias

</div>



<table class="data-table">


<thead>

<tr>

    <th>
        Becario
    </th>


    <th>
        Fecha
    </th>


    <th>
        Entrada
    </th>


    <th>
        Salida
    </th>


    <th>
        Pausas
    </th>


    <th>
        Trabajo
    </th>


    <th>
        Extra
    </th>


</tr>

</thead>



<tbody>


<?php $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<tr>


<td>

<?php echo e($asistencia->user->name); ?>


</td>


<td>

<?php echo e($asistencia->fecha); ?>


</td>


<td>

<?php echo e($asistencia->hora_entrada ?? '--'); ?>


</td>


<td>

<?php echo e($asistencia->hora_salida ?? '--'); ?>


</td>


<td>

<?php echo e($asistencia->tiempoPausas()); ?>


</td>


<td>

<?php echo e($asistencia->formatoTiempo(
        $asistencia->tiempoTrabajado()
    )); ?>


</td>


<td>

<?php echo e($asistencia->horasExtrasTotalFormato()); ?>


</td>



</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</tbody>


</table>




<div class="footer">

    OllinCheck |
    Generado:
    <?php echo e(now()->format('d/m/Y H:i')); ?>


</div>



</body>

</html><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/reportes/pdf/general.blade.php ENDPATH**/ ?>