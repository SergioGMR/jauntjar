<?php

namespace App\Filament\Widgets;

use App\Models\Classification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopClassificationsTable extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): ?string
    {
        return 'Ciudades Mejor Valoradas';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Classification::query()
                    ->with('city.country')
                    ->orderByDesc('total')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('city.display')
                    ->label('Ciudad')
                    ->searchable()
                    ->icon('heroicon-o-map-pin'),

                TextColumn::make('city.country.display')
                    ->label('País')
                    ->searchable()
                    ->icon('heroicon-o-flag'),

                TextColumn::make('cost')
                    ->label('Coste')
                    ->badge()
                    ->color('success'),

                TextColumn::make('culture')
                    ->label('Cultura')
                    ->badge()
                    ->color('info'),

                TextColumn::make('weather')
                    ->label('Clima')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('food')
                    ->label('Comida')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('total')
                    ->label('Total')
                    ->badge()
                    ->color('primary')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}
