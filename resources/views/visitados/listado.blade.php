<x-layouts.app title="Ciudades Visitadas">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/20">
                    <flux:icon.map class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ciudades Visitadas</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Explora los lugares que ya has conocido</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/20">
                        <flux:icon.map-pin class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Visitadas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" x-data="{ count: 0 }" x-init="$nextTick(() => { const interval = setInterval(() => { if (count < {{ App\Models\City::where('visited', true)->count() }}) count++; else clearInterval(interval); }, 50); });" x-text="count">0</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                        <flux:icon.flag class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Países Visitados</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" x-data="{ count: 0 }" x-init="$nextTick(() => { const interval = setInterval(() => { if (count < {{ App\Models\City::where('visited', true)->distinct('country_id')->count() }}) count++; else clearInterval(interval); }, 80); });" x-text="count">0</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/20">
                        <flux:icon.star class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Puntuación Media</p>
                        @php
                            $avgScore = App\Models\City::where('visited', true)
                                ->whereHas('classification')
                                ->with('classification')
                                ->get()
                                ->avg('classification.total');
                        @endphp
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $avgScore ? round($avgScore, 1) : '—' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/20">
                        <flux:icon.plus class="h-4 w-4 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Vuelos Directos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\City::where('visited', true)->where('stops', 0)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="flex-1 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
            <livewire:visitados-listado />
        </div>
    </div>
</x-layouts.app>
