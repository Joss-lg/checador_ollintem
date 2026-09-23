@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-6">

    <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm p-6">

        @include('admin.historial.components.resumen')
        @include('admin.historial.components.acciones')
        @include('admin.historial.components.tabla')

    </div>

</div>

@include('admin.historial.components.modal_editar_jornada')

@push('scripts')
<script>
    window.abrirEditarJornada = function (id, nombre, fecha, horaEntrada, horaSalida) {
        document.getElementById('editarBecarioNombre').textContent = nombre;
        document.getElementById('editarFecha').textContent         = fecha;
        document.getElementById('editarHoraEntrada').value         = horaEntrada;
        document.getElementById('editarHoraSalida').value          = horaSalida;
        document.getElementById('editarMotivo').value              = '';
        document.getElementById('formEditarJornada').action        =
            '/admin/historial/' + id + '/editar-horas';
        openModal('modalEditarJornada');
    };
</script>
@endpush

@endsection