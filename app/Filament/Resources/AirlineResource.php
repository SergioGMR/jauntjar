<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Models\Airline;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\AirlineResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AirlineResource\Pages\ManageAirlines;

class AirlineResource extends Resource
{
    protected static ?string $model = Airline::class;

    protected static ?string $modelLabel = 'aerolínea';

    protected static ?string $pluralModelLabel = 'aerolíneas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::PaperAirplane;

    protected static ?string $navigationLabel = 'Aerolíneas';

    protected static ?int $navigationSort = 4;

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
                    ->label('Nombre para mostrar')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                FileUpload::make('logo')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_low_cost')
                    ->label('Bajo costo')
                    ->helperText('¿Es una aerolínea de bajo costo?')
                    ->default(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('display')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_low_cost')
                    ->boolean(),
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
                    ->modalDescription('Editar la aerolínea'),
                DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar la aerolínea'),
                ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir la aerolínea'),
                RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar la aerolínea'),
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
            'index' => ManageAirlines::route('/'),
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
