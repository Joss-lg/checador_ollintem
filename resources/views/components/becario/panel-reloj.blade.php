{{-- resources/views/components/becario/panel-reloj.blade.php --}}
@props(['estadoInfo', 'estadoBanner'])

<div class="entrada bg-white dark:bg-slate-900/85 backdrop-blur-[15px] border border-[#EAE4D8] dark:border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full shadow-sm dark:shadow-lg transition-colors duration-300" style="animation-delay:.08s">

    <p class="uppercase text-gray-500 dark:text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-1">Tu turno en tiempo real</p>

    {{-- Reloj analógico --}}
    <div class="relative w-[200px] h-[200px] sm:w-[250px] sm:h-[250px] mx-auto mt-3">
        <div class="absolute inset-0 rounded-full bg-blue-500/10 dark:bg-blue-500/20 blur-3xl scale-90"></div>

        <div class="relative w-full h-full rounded-full border-8 border-gray-100 dark:border-[#001f3f] bg-white dark:bg-gradient-to-b dark:from-[#00172e] dark:to-[#000d1a] shadow-none dark:shadow-[inset_0_0_30px_#000,0_0_40px_rgba(0,31,63,0.9)] transition-colors duration-300">
            <span class="absolute font-mono text-xs font-bold text-gray-400 dark:text-blue-400/60 top-3 left-1/2 -translate-x-1/2">12</span>
            <span class="absolute font-mono text-xs font-bold text-gray-400 dark:text-blue-400/60 bottom-3 left-1/2 -translate-x-1/2">6</span>
            <span class="absolute font-mono text-xs font-bold text-gray-400 dark:text-blue-400/60 left-3 top-1/2 -translate-y-1/2">9</span>
            <span class="absolute font-mono text-xs font-bold text-gray-400 dark:text-blue-400/60 right-3 top-1/2 -translate-y-1/2">3</span>

            <div class="absolute top-1/2 left-1/2 w-3.5 h-3.5 -mt-[7px] -ml-[7px] bg-blue-600 dark:bg-blue-500 rounded-full z-[6] shadow-[0_0_15px_rgba(37,99,235,0.5)]"></div>

            <div id="reloj-hora" class="absolute bottom-1/2 left-1/2 origin-bottom rounded w-1.5 h-[46px] sm:h-[58px] -ml-[3px] bg-gradient-to-b from-blue-700 to-blue-400 dark:from-blue-600 dark:to-blue-300 z-[2]"></div>
            <div id="reloj-min" class="absolute bottom-1/2 left-1/2 origin-bottom rounded w-1 h-[68px] sm:h-[85px] -ml-[2px] bg-gradient-to-b from-cyan-600 to-blue-200 dark:from-cyan-400 dark:to-white z-[3]"></div>
            <div id="reloj-seg" class="absolute bottom-1/2 left-1/2 origin-bottom rounded-full w-[2px] h-[76px] sm:h-[95px] -ml-px bg-red-500/80 dark:bg-red-400/80 z-[4]"></div>
        </div>
    </div>

    <div class="text-center mt-5">
        <div class="text-4xl sm:text-5xl font-bold font-mono tracking-tight text-gray-900 dark:bg-gradient-to-b dark:from-white dark:to-slate-300 dark:bg-clip-text dark:text-transparent" id="reloj-digital">--:--:--</div>
        <p class="inline-block text-gray-500 dark:text-slate-400 text-xs mt-1.5 mb-0 capitalize border border-gray-200 dark:border-white/10 rounded-full px-3 py-1" id="reloj-fecha"></p>
    </div>

{{-- Banner de estado --}}
<div class="relative overflow-hidden rounded-2xl border {{ $estadoBanner['border'] }} dark:border-white/[0.08] bg-white dark:bg-slate-800/40 bg-gradient-to-r {{ $estadoBanner['wash'] }} to-transparent dark:shadow-[0_8px_24px_-8px_rgba(0,0,0,0.5)] mt-5 p-4 sm:p-5 flex justify-between items-center transition-colors duration-300">
    <div class="flex items-center gap-4">
        
        <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-xl sm:text-2xl text-white {{ $estadoBanner['iconBg'] }} {{ $estadoBanner['iconSh'] }} {{ $estadoBanner['pulse'] ? 'anillo-vivo' : '' }} shrink-0 transition-all">
           <ion-icon name="{{ $estadoInfo['icon'] }}"></ion-icon>
        </span>

        <div>
            <p class="uppercase text-gray-500 dark:text-slate-500 mb-1 tracking-[1px] text-[0.65rem]">Estado actual</p>
            <p class="font-bold text-lg sm:text-xl mb-0 leading-none {{ $estadoInfo['texto'] }}">{{ $estadoInfo['label'] }}</p>
            <p class="text-gray-600 dark:text-slate-400 text-sm mb-0 mt-1">{{ $estadoInfo['desc'] }}</p>
        </div>

    </div>
</div>
    {{-- Contadores de tiempo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
        <div class="group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-[#EAE4D8] dark:border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 transition-all duration-300 hover:border-green-500/30 hover:-translate-y-0.5">
            <span class="absolute left-0 top-0 h-full w-1 bg-green-500/70"></span>
            <span class="w-11 h-11 rounded-xl flex items-center justify-center text-lg text-white bg-gradient-to-br from-green-500 to-green-700 shadow-[0_8px_18px_-4px_rgba(34,197,94,0.5)] shrink-0">
               <ion-icon name="time-outline"></ion-icon>
            </span>
            <div class="min-w-0">
                <p class="uppercase text-gray-500 dark:text-slate-400 mb-0 text-[0.65rem] tracking-[1px]">Tiempo trabajado</p>
                <p class="font-bold font-mono text-xl mb-0 text-gray-900 dark:text-white" id="tiempoTrabajado">00:00:00</p>
            </div>
        </div>
        <div class="group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-[#EAE4D8] dark:border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 transition-all duration-300 hover:border-amber-500/30 hover:-translate-y-0.5">
            <span class="absolute left-0 top-0 h-full w-1 bg-amber-500/70"></span>
            <span class="w-11 h-11 rounded-xl flex items-center justify-center text-lg text-white bg-gradient-to-br from-amber-500 to-amber-700 shadow-[0_8px_18px_-4px_rgba(217,119,6,0.5)] shrink-0">
                <ion-icon name="cafe-outline"></ion-icon>
            </span>
            <div class="min-w-0">
                <p class="uppercase text-gray-500 dark:text-slate-400 mb-0 text-[0.65rem] tracking-[1px]">Tiempo en pausa</p>
                <p class="font-bold font-mono text-xl mb-0 text-gray-900 dark:text-white" id="tiempoPausa">00:00:00</p>
            </div>
        </div>
    </div>
</div>