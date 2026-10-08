@auth
    @if(auth()->user()->role === 'admin')

        {{-- Fondo oscuro: solo aplica en móvil cuando el drawer está abierto --}}
        <div class="fixed inset-0 bg-black/50 z-[1040] md:hidden"
             x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"></div>

        {{-- Sidebar: drawer en móvil, riel de iconos siempre visible en desktop --}}
        <aside class="fixed top-0 left-0 h-screen w-64 z-[1050] bg-stone-100 dark:bg-[#141419] border-r border-stone-200 dark:border-white/10 transition-all duration-300 ease-in-out md:translate-x-0 flex flex-col"
               :class="{
                   'translate-x-0': sidebarOpen,
                   '-translate-x-full': !sidebarOpen,
                   'md:w-20': sidebarCollapsed,
                   'md:w-64': !sidebarCollapsed
              }">

            {{-- Logo --}}
            <div class="flex items-center gap-3 h-16 shrink-0 px-4 md:justify-center"
                 :class="!sidebarCollapsed && 'md:justify-start'">
                <img src="{{ asset('images/isotipo.webp') }}" alt="Logo" class="w-8 h-8 object-contain shrink-0">
                <h5 class="text-gray-900 dark:text-white font-bold text-lg whitespace-nowrap overflow-hidden"
                    x-show="sidebarOpen || !sidebarCollapsed" x-cloak>
                    OLLIN<span class="text-blue-600">CHECK</span>
                </h5>
            </div>

            {{-- Toggle colapsar/expandir: justo debajo del logo, solo icono, solo desktop --}}
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden md:flex items-center justify-center mx-3 mb-2 rounded-xl p-3 text-gray-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 shrink-0">
                 <ion-icon name="reorder-four-outline"></ion-icon>
            </button>

            {{-- Navegación: centrada verticalmente, estilo Instagram --}}
            <nav class="flex flex-col gap-2 px-3 flex-grow justify-center overflow-y-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'icon' => 'speedometer-outline', 'label' => 'Dashboard'],
                        ['route' => 'home', 'icon' => 'people-outline', 'label' => 'Administración'],
                        ['route' => 'admin.historial', 'icon' => 'time-outline', 'label' => 'Historial'],
                    ];
                @endphp

                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="group relative flex items-center rounded-xl p-3 transition-colors duration-200 md:justify-center
                              {{ request()->routeIs($link['route'])
                                  ? 'bg-blue-600/10 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400'
                                  : 'text-gray-500 dark:text-gray-400 hover:bg-stone-200 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}"
                       :class="!sidebarCollapsed && 'md:justify-start'">

                        <ion-icon name="{{ $link['icon'] }}" class="text-xl shrink-0"></ion-icon>

                        <span class="ml-3 whitespace-nowrap overflow-hidden text-sm font-medium"
                              x-show="sidebarOpen || !sidebarCollapsed" x-cloak>
                            {{ $link['label'] }}
                        </span>

                        {{-- Tooltip: solo visible en desktop cuando está colapsado --}}
                        <span x-show="sidebarCollapsed"
                              class="hidden md:group-hover:flex absolute left-full ml-3 px-2.5 py-1.5 rounded-lg bg-gray-900 dark:bg-black text-white text-xs font-medium whitespace-nowrap shadow-lg z-[1060] max-w-[160px]">
                            {{ $link['label'] }}
                        </span>
                    </a>
                @endforeach
            </nav>

            {{-- Pie: solo logout --}}
            <div class="shrink-0 px-3 pb-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="group relative flex items-center w-full rounded-xl p-3 text-gray-500 hover:bg-red-500/10 hover:text-red-500 transition-all duration-300 md:justify-center"
                            :class="!sidebarCollapsed && 'md:justify-start'">
                            <ion-icon name="exit-outline"></ion-icon>
                        <span class="ml-3 whitespace-nowrap overflow-hidden text-sm font-medium"
                              x-show="sidebarOpen || !sidebarCollapsed" x-cloak>
                            Salir
                        </span>
                        <span x-show="sidebarCollapsed"
                              class="hidden md:group-hover:flex absolute left-full ml-3 px-2.5 py-1.5 rounded-lg bg-gray-900 dark:bg-black text-white text-xs font-medium whitespace-nowrap shadow-lg z-[1060] max-w-[160px]">
                            Salir
                        </span>
                    </button>
                </form>
            </div>
        </aside>
    @endif
@endauth