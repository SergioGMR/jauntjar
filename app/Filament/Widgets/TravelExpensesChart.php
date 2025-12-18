<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TravelExpensesChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return 'Gastos de Viajes';
    }

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(function ($monthsAgo) {
            return Carbon::now()->subMonths($monthsAgo);
        });

        $expenses = Budget::selectRaw('strftime("%Y-%m", departed_at) as month, SUM(total_price) as total')
            ->whereNotNull('departed_at')
            ->where('departed_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $labels = $months->map(fn ($date) => $date->translatedFormat('M Y'))->toArray();
        $data = $months->map(function ($date) use ($expenses) {
            $key = $date->format('Y-m');

            return isset($expenses[$key]) ? $expenses[$key] / 100 : 0;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Gastos (€)',
                    'data' => $data,
                    'fill' => true,
                    'backgroundColor' => 'rgba(251, 191, 36, 0.2)',
                    'borderColor' => 'rgba(251, 191, 36, 1)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
