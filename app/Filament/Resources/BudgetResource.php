<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Models\Budget;
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
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\BudgetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BudgetResource\Pages\ManageBudgets;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $modelLabel = 'presupuesto';

    protected static ?string $pluralModelLabel = 'presupuestos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wallet';

    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-wallet';

    protected static ?string $navigationLabel = 'Presupuestos';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('airline_id')
                    ->label('Aerolínea')
                    ->searchable()
                    ->relationship('airline', 'display')
                    ->createOptionForm([
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
                    ])
                    ->maxWidth('xl')
                    ->required(),
                Select::make('insurance_id')
                    ->label('Seguro')
                    ->searchable()
                    ->relationship('insurance', 'display')
                    ->createOptionForm([
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
                            ->required(),
                        TextInput::make('url')
                            ->required(),
                    ]),
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
                DatePicker::make('departed_at')
                    ->label('Fecha de salida'),
                DatePicker::make('arrived_at')
                    ->label('Fecha de vuelta'),
                TextInput::make('flight_ticket_price')
                    ->hint('grupo')
                    ->label('Precio del billete')
                    ->required()
                    ->live()
                    ->debounce(500)
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $total = (int) ($get('flight_ticket_price') ?? 0)
                            + (int) ($get('insurance_price') ?? 0)
                            + (int) ($get('accommodation_price') ?? 0)
                            + (int) ($get('transport_price') ?? 0);

                        $set('total_price', $total);
                    })
                    ->numeric(),
                TextInput::make('insurance_price')
                    ->label('Precio del seguro')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $total = (int) ($get('flight_ticket_price') ?? 0)
                            + (int) ($get('insurance_price') ?? 0)
                            + (int) ($get('accommodation_price') ?? 0)
                            + (int) ($get('transport_price') ?? 0);

                        $set('total_price', $total);
                    })
                    ->numeric(),
                TextInput::make('total_price')
                    ->label('Precio total')
                    ->numeric()
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('city.display')
                    ->sortable(),
                TextColumn::make('airline.display')
                    ->sortable(),
                TextColumn::make('insurance.display')
                    ->default('N/A')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('display')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('departed_at')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('arrived_at')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('flight_ticket_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('insurance_price')
                    ->label('Precio del seguro')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('accommodation_stars')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('accommodation_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transport_type')
                    ->searchable(),
                TextColumn::make('transport_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_price')
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
                    ->modalDescription('Editar el presupuesto'),
                DeleteAction::make()
                    ->label('')
                    ->modalHeading('Eliminar')
                    ->modalDescription('Eliminar el presupuesto'),
                ForceDeleteAction::make()
                    ->label('')
                    ->modalHeading('Destruir')
                    ->modalDescription('Destruir el presupuesto'),
                RestoreAction::make()
                    ->label('')
                    ->modalHeading('Recuperar')
                    ->modalDescription('Recuperar el presupuesto'),
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
            'index' => Pages\ManageBudgets::route('/'),
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
