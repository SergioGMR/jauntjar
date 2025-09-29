<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Models\Country;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Tables\Actions;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\CountryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CountryResource\Pages\ManageCountries;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $modelLabel = 'país';

    protected static ?string $pluralModelLabel = 'países';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-flag';

    protected static ?string $navigationLabel = 'Países';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
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
                TextColumn::make('name')
                    ->label('Nombre')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('display')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('currency')
                    ->label('Moneda')
                    ->searchable(),
                TextColumn::make('pibpc')
                    ->label('PIB per capita')
                    ->suffix(' €')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('womens_rights')
                    ->label('D. mujeres')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('lgtb_rights')
                    ->label('D. LGTBIQ+')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('visa')
                    ->label('Visa')
                    ->searchable(),
                TextColumn::make('language')
                    ->label('Idioma')
                    ->searchable(),
                TextColumn::make('roaming')
                    ->label('Roaming')
                    ->searchable(),
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
                    ->modalDescription('Editar el país'),
                DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar el país'),
                ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir el país'),
                RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar el país'),
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
