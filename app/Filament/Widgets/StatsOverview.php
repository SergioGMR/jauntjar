<?php

namespace App\Filament\Widgets;

use App\Models\Airline;
use App\Models\Budget;
use App\Models\City;
use App\Models\Country;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalCountries = Country::count();
        $totalCities = City::count();
        $visitedCities = City::where('visited', true)->count();
        $totalBudgets = Budget::count();
        $totalSpent = Budget::sum('total_price') / 100;
        $totalAirlines = Airline::count();

        $visitedPercentage = $totalCities > 0
            ? round(($visitedCities / $totalCities) * 100, 1)
            : 0;

        return [
            Stat::make('Países', $totalCountries)
                ->description('Destinos registrados')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),

            Stat::make('Ciudades', $totalCities)
                ->description($visitedCities . ' visitadas (' . $visitedPercentage . '%)')
                ->descriptionIcon('heroicon-m-map-pin')
                ->chart($this->getCitiesChartData())
                ->color('success'),

            Stat::make('Viajes', $totalBudgets)
                ->description(number_format($totalSpent, 2, ',', '.') . ' € gastados')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Aerolíneas', $totalAirlines)
                ->description('Compañías disponibles')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info'),
        ];
    }

    protected function getCitiesChartData(): array
    {
        return City::selectRaw('strftime("%Y-%m", created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->pluck('count')
            ->toArray();
    }
}
