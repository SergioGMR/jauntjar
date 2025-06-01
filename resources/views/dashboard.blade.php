<x-layouts.app title="Dashboard">
    @if (config("services.google.maps.key"))
        <livewire:components.maps-google />
    @else
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <span>No se ha encontrado la clave de Google Maps, crea primero una
                <a class="text-blue-500"
                   href="https://cloud.google.com/docs/authentication/api-keys?hl=es-419">aquí</a></span>
        </div>
    @endif
</x-layouts.app>
