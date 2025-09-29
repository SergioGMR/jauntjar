<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\CityResource\Pages\ManageCities;
use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $modelLabel = 'ciudad';

    protected static ?string $pluralModelLabel = 'ciudades';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-library';

    protected static string | \BackedEnum | null $activeNavigationIcon = 'heroicon-s-building-library';

    protected static ?string $navigationLabel = 'Ciudades';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Select::make('country_id')
                    ->label('País')
                    ->searchable()
                    ->relationship('country', 'display')
                    ->options(fn () => Country::pluck('display', 'id'))
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('uuid', (string) Str::uuid());
                    })
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('uuid', (string) Str::uuid());
                        $set('display', Str::title($state));
                        $set('slug', Str::slug($state));
                    })
                    ->required(),
                TextInput::make('display')
                    ->label('Nombre para mostrar')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Toggle::make('visited')
                    ->label('Visitado'),
                TextInput::make('stops')
                    ->label('Escalas')
                    ->numeric(),
                Grid::make()
                    ->schema([
                        TextInput::make('coordinates.lat')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.000001)
                            ->afterStateUpdated(function (Set $set, $state, callable $get) {
                                $set('coordinates', [
                                    'lat' => $state,
                                    'lng' => $get('coordinates.lng'),
                                ]);
                            }),
                        TextInput::make('coordinates.lng')
                            ->label('Longitud')
                            ->numeric()
                            ->step(0.000001)
                            ->afterStateUpdated(function (Set $set, $state, callable $get) {
                                $set('coordinates', [
                                    'lat' => $get('coordinates.lat'),
                                    'lng' => $state,
                                ]);
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('country.display')
                    ->label('País')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('display')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                IconColumn::make('visited')
                    ->label('Visitado')
                    ->boolean(),
                TextColumn::make('visited_at')
                    ->label('Fecha de visita')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->modalHeading('Editar')
                    ->modalDescription('Editar la ciudad'),
                DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar la ciudad'),
                ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir la ciudad'),
                RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar la ciudad'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCities::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
