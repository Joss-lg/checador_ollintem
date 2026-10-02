<div class="rounded-2xl bg-white dark:bg-[#15181d] border border-[#EAE4D8] dark:border-white/10 p-4 mb-6 transition-colors duration-300">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">

        {{-- Botón regresar --}}
        <div class="lg:col-span-3">
            <a
                href="{{ route('admin.historial') }}"
                class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-[#EAE4D8] dark:border-white/10 text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 px-4 py-2 text-sm font-medium transition-colors"
            >
                <ion-icon name="arrow-back-outline"></ion-icon>
                Regresar al historial
            </a>
        </div>

        {{-- Periodo (Formulario de Filtro GET) --}}
        <div class="lg:col-span-5">
            <form action="{{ request()->url() }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-2 m-0">

                <div class="relative">
                    <input
                        type="date"
                        name="fecha_inicio"
                        id="fecha_inicio"
                        value="{{ request('fecha_inicio') }}"
                        onchange="this.form.submit()"
                        class="w-full rounded-xl bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 text-gray-800 dark:text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-cyan-500/40 focus:border-blue-400 dark:focus:border-cyan-500/60 transition-all"
                        title="Fecha inicio"
                    >
                </div>

                <div class="relative">
                    <input
                        type="date"
                        name="fecha_fin"
                        id="fecha_fin"
                        value="{{ request('fecha_fin') }}"
                        onchange="this.form.submit()"
                        class="w-full rounded-xl bg-white dark:bg-white/[0.04] border border-[#EAE4D8] dark:border-white/10 text-gray-800 dark:text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-cyan-500/40 focus:border-blue-400 dark:focus:border-cyan-500/60 transition-all"
                        title="Fecha fin"
                    >
                </div>

            </form>
        </div>

        {{-- Acciones (Exportación pasa los query params activos) --}}
        <div class="lg:col-span-4">
            <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">

                <a href="{{ route('admin.reportes.excel', array_merge(['user' => $user], request()->only(['fecha_inicio', 'fecha_fin']))) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 shadow-sm transition-colors">
                   <ion-icon name="document-text-outline" class="text-base"></ion-icon>
                    Exportar Excel
                </a>

                <a href="{{ route('admin.reportes.pdf.becario', array_merge(['user' => $user], request()->only(['fecha_inicio', 'fecha_fin']))) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 shadow-sm transition-colors">
                    <ion-icon name="document-text-outline" class="text-base"></ion-icon>
                    Exportar PDF
                </a>

            </div>
        </div>

    </div>

</div>