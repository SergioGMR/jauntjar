<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages\ManageBudgets;
use App\Models\Budget;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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
        $default = City::where('slug','las-palmas')->first()?->id ?? null;
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
                Select::make('origin_city_id')
                    ->label('Origen')
                    ->relationship('originCity', 'display')
                    ->searchable()
                    ->default($default)
                    ->preload()
                    ->visible(fn (Get $get): bool => ! $get('is_open_jaw'))
                    ->required(fn (Get $get): bool => ! $get('is_open_jaw')),
                Toggle::make('is_open_jaw')
                    ->label('Open Jaw')
                    ->helperText('¿Es un viaje con múltiples destinos (open jaw)?')
                    ->default(false)
                    ->live()
                    ->columnSpanFull(),
                Select::make('origin_city_id')
                    ->label('Ciudad de origen')
                    ->relationship('originCity', 'display')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Select::make('country_id')
                            ->label('País')
                            ->searchable()
                            ->relationship('country', 'display')
                            ->options(fn (): array => Country::pluck('display', 'id')->all())
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('uuid', (string) Str::uuid());
                            })
                            ->required(),
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
                    ])
                    ->visible(fn (Get $get): bool => $get('is_open_jaw'))
                    ->required(fn (Get $get): bool => $get('is_open_jaw')),
                DatePicker::make('departed_at')
                    ->label('Fecha de salida'),
                DatePicker::make('arrived_at')
                    ->label('Fecha de vuelta')
                    ->hidden(fn (Get $get): bool => $get('is_open_jaw'))
                    ->helperText(fn (Get $get): ?string => $get('is_open_jaw') ? 'Se calcula automáticamente' : null),
                Placeholder::make('calculated_return_date')
                    ->label('Fecha de vuelta calculada')
                    ->content(function (Get $get, ?Budget $record): string {
                        if (! $record || ! $record->departed_at) {
                            return 'Selecciona fecha de salida';
                        }

                        $returnDate = $record->getCalculatedReturnDate();

                        return $returnDate ? $returnDate->format('d/m/Y') : 'Sin calcular';
                    })
                    ->visible(fn (Get $get): bool => $get('is_open_jaw')),
                Section::make('Tramos del viaje')
                    ->description('Define los tramos del itinerario. Ejemplo: LPA→LON (4 días), LON→CDG (3 días), CDG→LPA')
                    ->visible(fn (Get $get): bool => $get('is_open_jaw'))
                    ->schema([
                        Repeater::make('segments')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Select::make('origin_city_id')
                                    ->label('Origen')
                                    ->relationship('originCity', 'display')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('destination_city_id')
                                    ->label('Destino')
                                    ->relationship('destinationCity', 'display')
                                    ->searchable()
                                    ->createOptionForm([
                                        Select::make('country_id')
                                            ->label('País')
                                            ->searchable()
                                            ->relationship('country', 'display')
                                            ->options(fn (): array => Country::pluck('display', 'id')->all())
                                            ->live()
                                            ->afterStateUpdated(function (Set $set): void {
                                                $set('uuid', (string) Str::uuid());
                                            })
                                            ->required(),
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
                                    ])
                                    ->preload()
                                    ->required(),
                                TextInput::make('stay_days')
                                    ->label('Días de estancia')
                                    ->helperText('Días en el destino antes del siguiente vuelo')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->orderColumn('order')
                            ->reorderable()
                            ->addActionLabel('Añadir tramo')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => isset($state['origin_city_id'], $state['destination_city_id'])
                                    ? "Tramo: {$state['stay_days']} días"
                                    : null
                            ),
                    ])
                    ->columnSpanFull(),
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
                IconColumn::make('is_open_jaw')
                    ->label('Open Jaw')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrows-right-left')
                    ->falseIcon('heroicon-o-minus')
                    ->sortable(),
                TextColumn::make('originCity.display')
                    ->label('Origen')
                    ->placeholder('—')
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
                TextColumn::make('departed_at')
                    ->label('Salida')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('arrived_at')
                    ->label('Vuelta')
                    ->sortable()
                    ->getStateUsing(function (Budget $record): ?string {
                        if ($record->is_open_jaw) {
                            $returnDate = $record->getCalculatedReturnDate();

                            return $returnDate ? $returnDate->format('d/m/Y').' (calc.)' : null;
                        }

                        return $record->arrived_at?->format('d/m/Y');
                    }),
                TextColumn::make('segments_count')
                    ->label('Tramos')
                    ->counts('segments')
                    ->sortable(),
                TextColumn::make('flight_ticket_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('insurance_price')
                    ->label('Precio del seguro')
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
            'index' => ManageBudgets::route('/'),
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
