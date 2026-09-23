@extends('layouts.app')

@section('content')

<div class="w-full px-3 sm:px-4">

    <x-history.header />

    <div class="flex flex-wrap gap-3 mb-4">

        <a href="{{ route('admin.reportes.pdf.general', request()->query()) }}"
           class="inline-flex flex-1 sm:flex-none items-center justify-center sm:justify-start gap-2 rounded-lg px-4 py-2 no-underline font-semibold text-white text-[0.85rem] sm:text-[0.9rem] border border-transparent transition-all duration-200
                 bg-gradient-to-b from-red-600 to-red-700 border-red-400/30 shadow-[0_3px_10px_rgba(153,27,27,0.4)]
                 hover:text-white hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(153,27,27,0.55)] hover:brightness-110
                 focus-visible:text-white focus-visible:-translate-y-0.5 focus-visible:shadow-[0_6px_16px_rgba(153,27,27,0.55)] focus-visible:brightness-110
                 active:translate-y-0 active:scale-[0.97]">
            <ion-icon name="document-text-outline"></ion-icon>
            Descargar PDF
        </a>

        <a href="{{ route('admin.reportes.general.excel', request()->query()) }}"
           class="inline-flex flex-1 sm:flex-none items-center justify-center sm:justify-start gap-2 rounded-lg px-4 py-2 no-underline font-semibold text-white text-[0.85rem] sm:text-[0.9rem] border border-transparent transition-all duration-200
                 bg-gradient-to-b from-emerald-600 to-emerald-700 border-emerald-400/30 shadow-[0_3px_10px_rgba(6,95,70,0.4)]
                 hover:text-white hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(6,95,70,0.55)] hover:brightness-110
                 focus-visible:text-white focus-visible:-translate-y-0.5 focus-visible:shadow-[0_6px_16px_rgba(6,95,70,0.55)] focus-visible:brightness-110
                 active:translate-y-0 active:scale-[0.97]">
          <ion-icon name="document-text-outline"></ion-icon>
            Exportar Excel
        </a>

    </div>

    <x-history.stats
        :asistencias="$asistencias"
    />

    <x-history.search
        :meses="$meses"
    />

    <x-history.table
        :asistencias="$asistencias"
    />

    <x-history.pagination
        :asistencias="$asistencias"
    />

</div>

@include('admin.historial.components.modal_editar_jornada')

@push('scripts')
<script>
    // window.abrirEditarJornada — global para que el onclick del botón
    // en la tabla la encuentre sin importar el orden de carga del DOM.
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
