@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-6">

    <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm p-6">

        @include('admin.historial.components.resumen')
        @include('admin.historial.components.acciones')
        {{-- Vista del historial: calendario (por defecto) o lista. La elección
             se recuerda en el navegador del admin. --}}
        <div x-data="{
                vista: (() => { try { return localStorage.getItem('vistaHistorialBecario') || 'calendario' } catch (e) { return 'calendario' } })(),
                cambiar(v) { this.vista = v; try { localStorage.setItem('vistaHistorialBecario', v) } catch (e) {} }
             }">
            <div class="flex justify-end mb-3">
                <div class="inline-flex rounded-lg border border-[#EAE4D8] dark:border-gray-700 p-0.5 bg-gray-50 dark:bg-gray-800">
                    <button type="button" @click="cambiar('calendario')"
                            :class="vista === 'calendario' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                        <ion-icon name="calendar-outline"></ion-icon> Calendario
                    </button>
                    <button type="button" @click="cambiar('lista')"
                            :class="vista === 'lista' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                        <ion-icon name="list-outline"></ion-icon> Lista
                    </button>
                </div>
            </div>

            <div x-show="vista === 'calendario'">
                @include('admin.historial.components.calendario')
            </div>
            <div x-show="vista === 'lista'" x-cloak>
                @include('admin.historial.components.tabla')
            </div>
        </div>

        @include('admin.historial.components.correcciones')

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