<x-layouts.app title="Ciudades Planeadas">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/20">
                    <flux:icon.map-pin class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ciudades Planeadas</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Descubre los destinos que tienes en mente</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                        <flux:icon.calendar class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Planeadas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" x-data="{ count: 0 }" x-init="$nextTick(() => { const interval = setInterval(() => { if (count < {{ App\Models\City::where('visited', false)->count() }}) count++; else clearInterval(interval); }, 50); });" x-text="count">0</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                        <flux:icon.plus class="h-4 w-4 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">PIB Medio</p>
                        @php
                            $avgPib = App\Models\City::where('visited', false)
                                ->with('country')
                                ->get()
                                ->avg('country.pibpc');
                        @endphp
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ $avgPib ? number_format($avgPib, 0) : '—' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/20">
                        <flux:icon.plus class="h-4 w-4 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Requieren Visa</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\City::where('visited', false)->whereHas('country', function($q) { $q->where('visa', 'Sí'); })->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/20">
                        <flux:icon.wifi class="h-4 w-4 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Roaming Incluido</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\City::where('visited', false)->whereHas('country', function($q) { $q->where('roaming', 'Incluido'); })->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 p-6 dark:from-blue-900/10 dark:to-indigo-900/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">¿Listo para planear tu próximo viaje?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Accede al panel de administración para agregar nuevos destinos</p>
                </div>
                <flux:button 
                    variant="primary" 
                    size="sm"
                    href="{{ route('filament.backoffice.resources.budgets.index') }}"
                    target="_blank"
                    title="Abre el panel de administración en una nueva pestaña">
                    <flux:icon.plus class="h-4 w-4" />
                    Gestionar Destinos
                </flux:button>
            </div>
        </div>

        <!-- Table Section -->
        <div class="flex-1 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <livewire:planeados-listado />
        </div>
    </div>
</x-layouts.app>
