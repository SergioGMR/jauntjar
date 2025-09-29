<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\City;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\ClassificationResource\Pages\ManageClassification;
use App\Filament\Resources\ClassificationResource\Pages;
use App\Filament\Resources\ClassificationResource\RelationManagers;
use App\Models\Classification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassificationResource extends Resource
{
    protected static ?string $model = Classification::class;

    protected static ?string $modelLabel = 'clasificación';

    protected static ?string $pluralModelLabel = 'clasificaciones';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-trophy';

    protected static string | \BackedEnum | null $activeNavigationIcon = 'heroicon-s-trophy';

    protected static ?string $navigationLabel = 'Clasificaciones';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Select::make('city_id')
                    ->options(fn() => City::where('visited', true)->pluck('display', 'id'))
                    ->required(),
                TextInput::make('cost')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                TextInput::make('culture')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                TextInput::make('weather')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                TextInput::make('food')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                TextInput::make('total')
                    ->readOnly()
                    ->numeric(),
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
                TextColumn::make('city.display')
                    ->sortable(),
                TextColumn::make('cost')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('culture')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('weather')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('food')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
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
                    ->modalDescription('Editar la clasificación'),
                DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar la clasificación'),
                ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir la clasificación'),
                RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar la clasificación'),
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
            'index' => ManageClassification::route('/'),
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
