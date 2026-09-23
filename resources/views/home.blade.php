@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
        <h2 class="text-gray-900 dark:text-white font-bold mb-0 text-xl sm:text-3xl">
            <ion-icon name="people" class="text-blue-600 dark:text-blue-400 mr-2"></ion-icon>Administración de Usuarios
        </h2>
        
        @include('admin.modals.registrar-becario')
    </div>

    <div class="bg-white dark:bg-[#1b1e24] border border-[#EAE4D8] dark:border-white/10 shadow-xl rounded-2xl">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-gray-800 dark:text-gray-200 mb-0 align-middle">
                    <thead>
                        <tr class="text-gray-600 dark:text-gray-400 bg-[#F4F0E6] dark:bg-white/5 border-b border-[#EAE4D8] dark:border-white/10">
                            <th class="pl-3 sm:pl-4 py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Nombre</th>
                            <th class="py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Email</th>
                            <th class="py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Rol</th>
                            <th class="text-right pr-3 sm:pr-4 py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                        <tr class="hover:bg-[#F9F6EE] dark:hover:bg-white/5 border-b border-[#EAE4D8] dark:border-white/10 last:border-0 transition-colors">
                            <td class="pl-3 sm:pl-4 py-3 text-[0.8rem] sm:text-base">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-[#F4F0E6] dark:bg-white/10 border border-[#EAE4D8] dark:border-white/10 flex items-center justify-center text-blue-700 dark:text-blue-400 font-bold text-[0.75rem] sm:text-[0.9rem] flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-gray-800 dark:text-gray-100 font-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-[0.8rem] sm:text-base">
                                <span class="text-gray-600 dark:text-gray-400">{{ $user->email }}</span>
                            </td>
                            <td class="py-3">
                                <span class="inline-flex items-center rounded-full font-semibold text-[0.7rem] px-2.5 py-1 sm:text-xs sm:px-3 sm:py-1.5
                                    @if(strtolower($user->role) === 'admin') bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20
                                    @else bg-gray-100 text-gray-700 border border-gray-200 dark:bg-white/5 dark:text-gray-400 dark:border-white/10
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-right pr-3 sm:pr-4 py-3">
                                <div class="flex justify-end gap-2 flex-nowrap">
                                    
                                    <button type="button"
                                            class="w-7 h-7 sm:w-[34px] sm:h-[34px] rounded-full p-0 inline-flex items-center justify-center border-none text-white text-[0.75rem] sm:text-[0.95rem] transition-all duration-200 bg-gradient-to-br from-cyan-500 to-cyan-600 shadow-[0_2px_6px_rgba(8,145,178,0.3)] hover:text-white hover:-translate-y-[2px] hover:scale-[1.05] hover:shadow-[0_4px_12px_rgba(8,145,178,0.4)] focus:outline-none focus:ring-2 focus:ring-cyan-300 dark:focus:ring-cyan-400/50"
                                            title="Editar"
                                            onclick="prepararEdicion('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->role }}', '{{ $user->email }}')">
                                        <ion-icon name="pencil"></ion-icon>
                                    </button>

                                    @if($user->id != 1)
                                    <button type="button"
                                            onclick="confirmarEliminar({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            class="w-7 h-7 sm:w-[34px] sm:h-[34px] rounded-full p-0 inline-flex items-center justify-center border-none text-white text-[0.75rem] sm:text-[0.95rem] transition-all duration-200 bg-gradient-to-br from-red-500 to-red-600 shadow-[0_2px_6px_rgba(220,38,38,0.3)] hover:text-white hover:-translate-y-[2px] hover:scale-[1.05] hover:shadow-[0_4px_12px_rgba(220,38,38,0.4)] focus:outline-none focus:ring-2 focus:ring-red-300 dark:focus:ring-red-400/50"
                                            title="Eliminar">
                                        <ion-icon name="trash"></ion-icon>
                                    </button>

                                    {{-- Form oculto — solo se envía si el admin confirma en el modal --}}
                                    <form id="formEliminarUsuario-{{ $user->id }}"
                                          action="{{ route('users.delete', $user->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.modals.editar-usuario')

{{-- Modal de confirmación de eliminación --}}
<div id="modalConfirmarEliminar"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 dark:bg-black/70 backdrop-blur-sm dark:backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 ease-out p-4"
     role="dialog" aria-modal="true">

    <div class="modal-dialog relative w-full max-w-sm flex flex-col bg-white dark:bg-[#15181d] text-gray-800 dark:text-white border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-2xl dark:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.7)] overflow-hidden transform scale-95 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-90"></div>

        <div class="flex items-center gap-4 px-6 pt-6 pb-4 bg-[#F4F0E6] dark:bg-[#1a1d23]">
            <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 text-xl dark:shadow-[0_0_20px_rgba(239,68,68,0.2)]">
                <ion-icon name="trash-outline"></ion-icon>
            </div>
            <div>
                <p class="text-xs font-bold tracking-widest text-gray-500 dark:text-gray-500 uppercase mb-1">Confirmar acción</p>
                <h5 class="text-lg font-bold text-gray-900 dark:text-white m-0">Eliminar usuario</h5>
            </div>
        </div>

        <div class="h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

        <div class="px-6 py-5">
            <p class="text-sm text-gray-600 dark:text-gray-400 m-0">
                ¿Seguro que quieres eliminar a
                <span id="eliminarUsuarioNombre" class="font-semibold text-gray-900 dark:text-white"></span>?
                Esta acción no se puede deshacer.
            </p>
        </div>

        <div class="h-px bg-[#EAE4D8] dark:bg-white/[0.08]"></div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-[#F4F0E6] dark:bg-white/[0.03]">
            <button type="button" onclick="closeModal('modalConfirmarEliminar')"
                    class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5 border border-[#EAE4D8] dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/10 dark:hover:text-white transition-colors focus:outline-none">
                Cancelar
            </button>
            <button type="button" id="btnConfirmarEliminar"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg shadow-[0_4px_14px_0_rgba(220,38,38,0.4)] hover:bg-red-500 hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-red-500/40 focus:outline-none">
                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let _formEliminarId = null;

function confirmarEliminar(id, nombre) {
    _formEliminarId = id;
    document.getElementById('eliminarUsuarioNombre').textContent = nombre;
    openModal('modalConfirmarEliminar');
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', function () {
    if (_formEliminarId) {
        document.getElementById('formEliminarUsuario-' + _formEliminarId).submit();
    }
});
</script>
@endpush