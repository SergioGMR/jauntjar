<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
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

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $modelLabel = 'país';

    protected static ?string $pluralModelLabel = 'países';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $activeNavigationIcon = 'heroicon-s-flag';

    protected static ?string $navigationLabel = 'Países';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\TextInput::make('name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('uuid', (string) Str::uuid());
                        $set('display', Str::title($state));
                        $set('slug', Str::slug($state));
                    })
                    ->required(),
                Forms\Components\TextInput::make('display')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Código')
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->label('Moneda')
                    ->required(),
                Forms\Components\TextInput::make('pibpc')
                    ->label('PIB per capita')
                    ->required()
                    ->integer(),
                Forms\Components\Select::make('visa')
                    ->options([
                        'No' => 'No',
                        'Sí' => 'Sí',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('language')
                    ->label('Idioma')
                    ->required(),
                Forms\Components\Select::make('roaming')
                    ->options([
                        'N/A' => 'N/A',
                        '1' => 'Zona 1',
                        '2' => 'Zona 2',
                        '3' => 'Zona 3',
                    ])
                    ->required(),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('display')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Moneda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pibpc')
                    ->label('PIB per capita')
                    ->suffix(' €')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visa')
                    ->label('Visa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->label('Idioma')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roaming')
                    ->label('Roaming')
                    ->searchable(),
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
                    ->modalDescription('Editar el país'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar el país'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir el país'),
                Tables\Actions\RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar el país'),
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
            'index' => Pages\ManageCountries::route('/'),
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
