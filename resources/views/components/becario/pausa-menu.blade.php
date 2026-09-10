@props(['puedePausar'])

<div id="pausaMenu" class="max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-out">
    <form action="{{ route('pausa.iniciar') }}" method="POST"
          class="bg-white dark:bg-slate-800/50 border border-amber-500/30 rounded-2xl p-3 mt-2 transition-colors duration-300">
        @csrf
        <label class="block text-sm text-amber-600 dark:text-amber-400 font-bold uppercase mb-2 text-[0.7rem]">
            Motivo de pausa
        </label>
        <select name="motivo" class="w-full bg-white dark:bg-[#0f1724] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white text-sm rounded-lg px-3 py-2 mb-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/25 focus:outline-none transition-colors">
            <option value="Almuerzo">Almuerzo</option>
            <option value="Personal">Personal</option>
        </select>
        <button type="submit"
                class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold uppercase text-sm rounded-lg px-4 py-2.5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                @disabled(!$puedePausar)>
            Iniciar ahora
        </button>
    </form>
</div>