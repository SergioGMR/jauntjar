<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages\ManageCities;
use App\Models\City;
use App\Models\Country;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $modelLabel = 'ciudad';

    protected static ?string $pluralModelLabel = 'ciudades';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-building-library';

    protected static ?string $navigationLabel = 'Ciudades';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Select::make('country_id')
                    ->label('País')
                    ->searchable()
                    ->relationship('country', 'display')
                    ->options(fn (): array => Country::pluck('display', 'id')->all())
                    ->createOptionForm([
                       Grid::make()
                       ->schema([
                         TextInput::make('uuid')
                            ->label('UUID')
                            ->required()
                            ->disabled()
                            ->dehydrated(true),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('uuid', (string) Str::uuid());
                                $set('display', Str::title($state));
                                $set('slug', Str::slug($state));
                            })
                            ->required(),
                        TextInput::make('display')
                            ->required(),
                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),
                        TextInput::make('code')
                            ->label('Código')
                            ->required(),
                        TextInput::make('currency')
                            ->label('Moneda')
                            ->required(),
                        TextInput::make('pibpc')
                            ->label('PIB per capita')
                            ->required()
                            ->integer(),
                        TextInput::make('womens_rights')
                            ->label('Derechos de las mujeres')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(10),
                        TextInput::make('lgtb_rights')
                            ->label('Derechos de LGTBIQ+')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(10),
                        Select::make('visa')
                            ->options([
                                'No' => 'No',
                                'Sí' => 'Sí',
                            ])
                            ->required(),
                        TextInput::make('language')
                            ->label('Idioma')
                            ->required(),
                        Select::make('roaming')
                            ->options([
                                'N/A' => 'N/A',
                                '1' => 'Zona 1',
                                '2' => 'Zona 2',
                                '3' => 'Zona 3',
                            ])
                            ->required(),
                       ])->columns(2)
                    ])
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('uuid', (string) Str::uuid());
                    })
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
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
                    ->live()
                    ->label('Visitado'),
                DatePicker::make('visited_at')
                    ->label('Fecha de visita')
                    ->live()
                    ->visible(fn (Get $get): bool => $get('visited') === true),
                TextInput::make('stops')
                    ->label('Escalas')
                    ->numeric(),
                Grid::make()
                    ->schema([
                        TextInput::make('coordinates.lat')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.000001)
                            ->afterStateUpdated(function (Set $set, Get $get, ?float $state): void {
                                $set('coordinates', [
                                    'lat' => $state,
                                    'lng' => $get('coordinates.lng'),
                                ]);
                            }),
                        TextInput::make('coordinates.lng')
                            ->label('Longitud')
                            ->numeric()
                            ->step(0.000001)
                            ->afterStateUpdated(function (Set $set, Get $get, ?float $state): void {
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
