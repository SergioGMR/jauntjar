<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassificationResource\Pages;
use App\Filament\Resources\ClassificationResource\RelationManagers;
use App\Models\Classification;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $activeNavigationIcon = 'heroicon-s-trophy';

    protected static ?string $navigationLabel = 'Clasificaciones';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->options(fn() => \App\Models\City::where('visited', true)->pluck('display', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('culture')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('weather')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('food')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('cost') + $get('culture') + $get('weather') + $get('food');
                        $total = $total / 4;

                        $set('total', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->readOnly()
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('city.display')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('culture')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weather')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('food')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
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
                    ->modalDescription('Editar la clasificación'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar la clasificación'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir la clasificación'),
                Tables\Actions\RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar la clasificación'),
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
            'index' => Pages\ManageClassification::route('/'),
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
