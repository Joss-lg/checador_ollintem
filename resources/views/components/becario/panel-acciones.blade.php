{{-- resources/views/components/becario/panel-acciones.blade.php --}}
@props(['presenter'])

<div class="flex flex-col gap-3.5">

    {{-- Registrar entrada --}}
    <form action="{{ route('entrada') }}" method="POST" class="m-0">
        @csrf
        <x-becario.accion-boton
            color="blue"
            icon="enter-outline"
            titulo="Registrar entrada"
            descripcion="Inicia el registro de tu turno"
            :disabled="! $presenter->puedeEntrada"
            :destacar="$presenter->destacar('entrada')"
            as="submit"
        />
    </form>

    {{-- Gestionar pausa --}}
    <div>
        <x-becario.accion-boton
            color="amber"
            icon="cafe-outline"
            iconRotate="rotate-3"
            titulo="Gestionar pausa"
            descripcion="Justificación obligatoria"
            :disabled="! $presenter->puedePausar"
            :destacar="$presenter->destacar('pausar')"
            as="button"
            onclick="togglePausaMenu()"
        />
        <x-becario.pausa-menu :puede-pausar="$presenter->puedePausar" />
    </div>

    {{-- Finalizar pausa --}}
    <form id="formFinalizarPausa" action="{{ route('pausa.finalizar') }}" method="POST" class="m-0">
        @csrf
        <x-becario.accion-boton
            color="blue"
            icon="play-outline"
            titulo="Finalizar pausa"
            descripcion="Reanuda tus actividades"
            :disabled="! $presenter->puedeReanudar"
            :destacar="$presenter->destacar('reanudar')"
            as="button"
            onclick="openModal('modalFinalizarPausa')"
        />
    </form>

    {{-- Registrar salida --}}
    <form id="formSalida" action="{{ route('salida') }}" method="POST" class="m-0">
        @csrf
        <x-becario.accion-boton
            color="red"
            icon="exit-outline"
            iconRotate="rotate-3"
            titulo="Registrar salida"
            descripcion="Finaliza tu turno"
            :disabled="! $presenter->puedeSalir"
            :destacar="$presenter->destacar('salir')"
            as="button"
            onclick="openModal('modalSalida')"
        />
    </form>

    {{-- Info Box --}}
    <div class="bg-slate-50 dark:bg-slate-800/50 border border-gray-200 dark:border-white/[0.06] rounded-2xl mt-auto p-4 flex gap-3 items-start transition-colors duration-300">
        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/15 border border-blue-200 dark:border-blue-400/25 flex items-center justify-center shrink-0">
            <ion-icon name="information-circle-outline"></ion-icon>
        </span>
        <div>
            <p class="font-bold text-sm mb-0 text-gray-900 dark:text-white">Recuerda registrar tus pausas</p>
            <p class="text-gray-500 dark:text-slate-400 mb-0 text-[0.75rem]">Para llevar un control correcto de tu tiempo laboral.</p>
        </div>
    </div>

</div>