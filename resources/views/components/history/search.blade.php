@props(['meses'])

<form method="GET" class="mb-4">

    <div class="bg-white dark:bg-[#15181d] border border-[#EAE4D8] dark:border-white/10 rounded-2xl shadow-sm">

        <div class="p-5">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-end">

                {{-- Buscar --}}
                <div class="lg:col-span-4">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Buscar becario
                    </label>
                    <input type="text" name="search"
                           class="w-full bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/40 focus:outline-none"
                           placeholder="Nombre o correo..."
                           value="{{ request('search') }}">
                </div>

                {{-- Semana --}}
                <div class="lg:col-span-2">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Semana
                    </label>
                    <select name="semana"
                            class="w-full bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/40 focus:outline-none">
                        <option value="">Todas</option>
                        <option value="1" @selected(request('semana')==1)>1 - 7</option>
                        <option value="2" @selected(request('semana')==2)>8 - 14</option>
                        <option value="3" @selected(request('semana')==3)>15 - 21</option>
                        <option value="4" @selected(request('semana')==4)>22 - 28</option>
                        <option value="5" @selected(request('semana')==5)>29 - Fin</option>
                    </select>
                </div>

                {{-- Mes --}}
                <div class="lg:col-span-2">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Mes
                    </label>
                    <select name="mes"
                            class="w-full bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/40 focus:outline-none">
                        <option value="">Todos</option>
                        @foreach($meses as $mes)
                            <option value="{{ $mes->numero_mes }}" @selected(request('mes')==$mes->numero_mes)>
                                {{ \Carbon\Carbon::create()->month($mes->numero_mes)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Orden --}}
                <div class="lg:col-span-2">
                    <label class="block text-gray-600 dark:text-gray-400 text-sm mb-1.5 font-medium">
                        Orden
                    </label>
                    <select name="order"
                            class="w-full bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/40 focus:outline-none">
                        <option value="az" @selected(request('order')=='az')>A-Z</option>
                        <option value="za" @selected(request('order')=='za')>Z-A</option>
                        <option value="recent" @selected(request('order')=='recent')>Más reciente</option>
                        <option value="oldest" @selected(request('order')=='oldest')>Más antiguo</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="lg:col-span-2">
                    <div class="grid gap-2">
                        <button class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2.5 transition-colors shadow-sm">
                           <ion-icon name="search-outline"></ion-icon> Buscar
                        </button>
                        <a href="{{ route('admin.historial') }}"
                           class="inline-flex items-center justify-center gap-2 border border-[#EAE4D8] dark:border-white/10 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg px-4 py-2.5 transition-colors no-underline">
                            <ion-icon name="refresh-outline"></ion-icon> Limpiar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>