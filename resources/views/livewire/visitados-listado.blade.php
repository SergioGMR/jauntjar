<div class="w-full h-full py-4 px-4">

    <div class="grid grid-cols-8 gap-1 mb-4 bg-blue-400 text-black px-2">
        <div class="col-span-1 capitalize font-bold">país</div>
        <div class="col-span-1 capitalize font-bold">ciudad</div>
        <div class="col-span-1 capitalize font-bold">idioma</div>
        <div class="col-span-1 capitalize font-bold">moneda</div>
        <div class="col-span-1 capitalize font-bold">fecha</div>
        <div class="col-span-1 capitalize font-bold">clasificación</div>
        <div class="col-span-1 capitalize font-bold">experiencia</div>
        <div class="col-span-1 capitalize font-bold">acciones</div>
    </div>

    @forelse ($visitados as $visitado)
        <div class="grid grid-cols-8 grid-flow-row auto-rows-max">
            <div class="col-span-1">{{ $visitado->country->display }}</div>
            <div class="col-span-1">{{ $visitado->display }}</div>
            <div class="col-span-1">{{ $visitado->country->language }}</div>
            <div class="col-span-1">{{ $visitado->country->currency }}</div>
            <div class="col-span-1">{{ Carbon\Carbon::parse($visitado->visited_at)->format('d/m/Y') }}</div>
            <div class="col-span-1">clasificación</div>
            <div class="col-span-1">experiencia</div>
            <div class="col-span-1">acciones</div>
        </div>
    @empty
        <div class="grid grid-cols-8 grid-flow-row auto-rows-max">
            <div class="col-span-8">No hay registros</div>
        </div>
    @endforelse
    {{ $visitados->links() }}
</div>
