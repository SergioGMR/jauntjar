<div class="w-full h-full py-4 px-4">

    <div class="grid grid-cols-8 gap-1 mb-4 bg-blue-400 text-black px-2">
        <div class="col-span-1 capitalize font-bold">país</div>
        <div class="col-span-1 capitalize font-bold">ciudad</div>
        <div class="col-span-1 capitalize font-bold">idioma</div>
        <div class="col-span-1 capitalize font-bold">moneda</div>
        <div class="col-span-1 capitalize font-bold">PIBPC</div>
        <div class="col-span-1 capitalize font-bold">roaming</div>
        <div class="col-span-1 capitalize font-bold">visado</div>
        <div class="col-span-1 capitalize font-bold">escalas</div>
    </div>

    @forelse ($planeados as $planeado)
        <div class="grid grid-cols-8 grid-flow-row auto-rows-max">
            <div class="col-span-1">{{ $planeado->country->display }}</div>
            <div class="col-span-1">{{ $planeado->display }}</div>
            <div class="col-span-1">{{ $planeado->country->language }}</div>
            <div class="col-span-1">{{ $planeado->country->currency }}</div>
            <div class="col-span-1">${{ number_format($planeado->country->pibpc, 2) }}</div>
            <div class="col-span-1">{{ $planeado->country->roaming }}</div>
            <div class="col-span-1">{{ $planeado->country->visa }}</div>
            <div class="col-span-1">{{ $planeado->stops==0?'Sin escalas':$planeado->stops }}</div>
        </div>
    @empty
        <div class="grid grid-cols-8 grid-flow-row auto-rows-max">
            <div class="col-span-8">No hay registros</div>
        </div>
    @endforelse
    {{ $planeados->links() }}
</div>
