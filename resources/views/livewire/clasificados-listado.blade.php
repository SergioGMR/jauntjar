<div class="w-full h-full py-4 px-4">
    <!-- Barra de búsqueda -->
    <div class="mb-4">
        <div class="relative">
            <input type="text" wire:model.live="search"
                class="w-full px-4 py-2 pr-12 rounded-lg border shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Buscar...">
            <button wire:click="$dispatch('clearSearch')"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-7 gap-1 mb-4 bg-red-300 text-black px-2">
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'city.country.display'})"
                class="flex items-center capitalize cursor-pointer">
                país
                @if ($sortBy === 'city.country.display')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'city.display'})"
                class="flex items-center capitalize cursor-pointer">
                ciudad
                @if ($sortBy === 'city.display')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'cost'})"
                class="flex items-center capitalize cursor-pointer">
                coste
                @if ($sortBy === 'cost')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'culture'})"
                class="flex items-center capitalize cursor-pointer">
                cultura
                @if ($sortBy === 'culture')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'weather'})"
                class="flex items-center capitalize cursor-pointer">
                clima
                @if ($sortBy === 'weather')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'food'})"
                class="flex items-center capitalize cursor-pointer">
                comida
                @if ($sortBy === 'food')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
        <div class="col-span-1 font-bold">
            <button wire:click="$dispatch('sortBy', {field: 'total'})"
                class="flex items-center capitalize cursor-pointer">
                total
                @if ($sortBy === 'total')
                    @if ($sortDirection === 'asc')
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @endif
            </button>
        </div>
    </div>

    @forelse ($clasificados as $clasificado)
        <div class="grid grid-cols-7 grid-flow-row auto-rows-max">
            <div class="col-span-1">{{ $clasificado->city->country->display }}</div>
            <div class="col-span-1">{{ $clasificado->city->display }}</div>
            <div class="col-span-1">{{ number_format($clasificado->cost / 20, 1) }}</div>
            <div class="col-span-1">{{ number_format($clasificado->culture / 20, 1) }}</div>
            <div class="col-span-1">{{ number_format($clasificado->weather / 20, 1) }}</div>
            <div class="col-span-1">{{ number_format($clasificado->food / 20, 1) }}</div>
            <div class="col-span-1">{{ number_format($clasificado->total / 20, 1) }}</div>
        </div>
    @empty
        <div class="grid grid-cols-7 grid-flow-row auto-rows-max">
            <div class="col-span-7">No hay registros</div>
        </div>
    @endforelse

    {{ $clasificados->links() }}
</div>
