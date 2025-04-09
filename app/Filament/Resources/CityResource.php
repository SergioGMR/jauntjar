<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $modelLabel = 'ciudad';

    protected static ?string $pluralModelLabel = 'ciudades';

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $activeNavigationIcon = 'heroicon-s-building-library';

    protected static ?string $navigationLabel = 'Ciudades';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\Select::make('country_id')
                    ->options(fn() => Country::pluck('display', 'id'))
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('uuid', (string) Str::uuid());
                    })
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('display')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\Toggle::make('visited'),
                Forms\Components\DatePicker::make('visited_at'),
                Forms\Components\TextInput::make('days')
                    ->numeric(),
                Forms\Components\TextInput::make('stops')
                    ->numeric(),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('coordinates.lat')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.000001)
                            ->afterStateUpdated(function (Set $set, $state, callable $get) {
                                $set('coordinates', [
                                    'lat' => $state,
                                    'lng' => $get('coordinates.lng'),
                                ]);
                            }),
                        Forms\Components\TextInput::make('coordinates.lng')
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
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.display')
                    ->label('País')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('display')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('visited')
                    ->label('Visitado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('visited_at')
                    ->label('Fecha de visita')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->modalHeading('Editar')
                    ->modalDescription('Editar la ciudad'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar la ciudad'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir la ciudad'),
                Tables\Actions\RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar la ciudad'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCities::route('/'),
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
