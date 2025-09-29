<div class="w-full h-full py-4 px-4">

    <div class="grid grid-cols-6 gap-1 mb-4 bg-emerald-400 text-black px-2">
        <div class="col-span-1 capitalize font-bold">país</div>
        <div class="col-span-1 capitalize font-bold">ciudad</div>
        <div class="col-span-1 capitalize font-bold">idioma</div>
        <div class="col-span-1 capitalize font-bold">moneda</div>
        <div class="col-span-1 capitalize font-bold">puntuación</div>
        <div class="col-span-1 capitalize font-bold">paradas</div>
    </div>

    @forelse ($visitados as $visitado)
        <div class="grid grid-cols-6 grid-flow-row auto-rows-max">
            <div class="col-span-1">{{ $visitado->country->display }}</div>
            <div class="col-span-1">{{ $visitado->display }}</div>
            <div class="col-span-1">{{ $visitado->country->language }}</div>
            <div class="col-span-1">{{ $visitado->country->currency }}</div>
            <div class="col-span-1">{{ $visitado->classification ? $visitado->classification->total . '/100' : 'Sin clasificar' }}</div>
            <div class="col-span-1">{{ $visitado->stops == 0 ? 'Directo' : $visitado->stops . ' paradas' }}</div>
        </div>
    @empty
        <div class="grid grid-cols-6 grid-flow-row auto-rows-max">
            <div class="col-span-6">No hay registros</div>
        </div>
    @endforelse
    {{ $visitados->links() }}
</div>
