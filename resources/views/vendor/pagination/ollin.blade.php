@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();

    // Clases (strings completos para que Tailwind los detecte)
    $btn     = 'inline-flex items-center justify-center rounded-lg min-w-[1.9rem] h-8 px-1 text-[0.8rem] sm:min-w-[2.25rem] sm:h-9 sm:px-3 sm:text-sm';
    $btnIdle = 'bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5';
    $btnOff  = 'bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600';
    $btnOn   = 'font-semibold bg-blue-600 border border-blue-600 text-white shadow-sm';
    $dots    = 'inline-flex items-center justify-center min-w-[1rem] h-8 sm:h-9 text-gray-500 dark:text-gray-500';

    // Arma la lista: siempre 1 y última, más la actual ± $w, con "..." en los huecos
    $buildPages = function (int $w) use ($current, $last) {
        $pages = collect([1, $last])
            ->merge(range(max(1, $current - $w), min($last, $current + $w)))
            ->unique()->sort()->values();

        $out  = [];
        $prev = null;
        foreach ($pages as $p) {
            if ($prev !== null) {
                if ($p - $prev === 2) {
                    $out[] = $prev + 1;   // un solo hueco: mejor mostrar el número
                } elseif ($p - $prev > 2) {
                    $out[] = '...';
                }
            }
            $out[] = $p;
            $prev  = $p;
        }
        return $out;
    };

    // Móvil: ventana de 1 (cabe en ~360px). Escritorio: ventana de 2.
    $variants = [
        ['wrapper' => 'flex sm:hidden',  'window' => 1],
        ['wrapper' => 'hidden sm:flex',  'window' => 2],
    ];
@endphp

{{-- El texto "Mostrando X a Y de Z" ya lo imprime components/history/pagination.blade.php --}}
<nav role="navigation" aria-label="Paginación" class="w-full">

    @foreach ($variants as $variant)
        <ul class="{{ $variant['wrapper'] }} flex-wrap items-center justify-center sm:justify-end gap-1 mb-0 list-none p-0">

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <li><span class="{{ $btn }} {{ $btnOff }}"><ion-icon name="chevron-back-outline"></ion-icon></span></li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" aria-label="Anterior" class="{{ $btn }} {{ $btnIdle }}">
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </a>
                </li>
            @endif

            {{-- Números --}}
            @foreach ($buildPages($variant['window']) as $item)
                @if ($item === '...')
                    <li><span class="{{ $dots }}">…</span></li>
                @elseif ($item == $current)
                    <li><span aria-current="page" class="{{ $btn }} {{ $btnOn }}">{{ $item }}</span></li>
                @else
                    <li><a href="{{ $paginator->url($item) }}" aria-label="Ir a la página {{ $item }}" class="{{ $btn }} {{ $btnIdle }}">{{ $item }}</a></li>
                @endif
            @endforeach

            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="Siguiente" class="{{ $btn }} {{ $btnIdle }}">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </a>
                </li>
            @else
                <li><span class="{{ $btn }} {{ $btnOff }}"><ion-icon name="chevron-forward-outline"></ion-icon></span></li>
            @endif

        </ul>
    @endforeach

</nav>
@endif