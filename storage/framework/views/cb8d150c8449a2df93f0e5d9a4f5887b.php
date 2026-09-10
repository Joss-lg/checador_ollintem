<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Reporte Individual - OllinCheck</title>


<style>
    <?php echo $__env->make('admin.reportes.pdf.partials.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</style>

</head>



<body>



<div class="header">

<h1>OLLIN CHECK</h1>

<p>
Reporte individual de asistencia
</p>

</div>




<div class="section-title">

Información del becario

</div>


<table class="info-table">

<tr>

<td>
<strong>Nombre:</strong>
<?php echo e($user->name); ?>

</td>


<td>
<strong>Correo:</strong>
<?php echo e($user->email); ?>

</td>


</tr>


</table>




<div class="section-title">

Resumen del periodo

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



</tr>

</table>





<div class="section-title">

Detalle de jornadas

</div>




<table class="data-table">


<thead>

<tr>

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


</tr>

</thead>


<tbody>


<?php $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<tr>


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


</tr>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</tbody>


</table>




<div class="footer">

OLLIN CHECK |

Generado:

<?php echo e(now()->format('d/m/Y H:i')); ?>


</div>



</body>

</html><?php /**PATH C:\Users\tortu\Checador-Online\resources\views/admin/reportes/pdf/individual.blade.php ENDPATH**/ ?>