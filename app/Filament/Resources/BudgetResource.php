<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages;
use App\Models\Budget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $modelLabel = 'presupuesto';

    protected static ?string $pluralModelLabel = 'presupuestos';

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $activeNavigationIcon = 'heroicon-s-wallet';

    protected static ?string $navigationLabel = 'Presupuestos';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('airline_id')
                    ->label('Aerolínea')
                    ->searchable()
                    ->relationship('airline', 'display')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('uuid')
                            ->label('UUID')
                            ->required()
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('uuid', (string) Str::uuid());
                                $set('display', Str::title($state));
                                $set('slug', Str::slug($state));
                            })
                            ->required(),
                        Forms\Components\TextInput::make('display')
                            ->label('Nombre para mostrar')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required(),
                        Forms\Components\FileUpload::make('logo')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_low_cost')
                            ->label('Bajo costo')
                            ->helperText('¿Es una aerolínea de bajo costo?')
                            ->default(false)
                            ->required(),
                    ])
                    ->maxWidth('xl')
                    ->required(),
                Forms\Components\Select::make('insurance_id')
                    ->label('Seguro')
                    ->searchable()
                    ->relationship('insurance', 'display')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('uuid')
                            ->label('UUID')
                            ->required()
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('uuid', (string) Str::uuid());
                                $set('display', Str::title($state));
                                $set('slug', Str::slug($state));
                            })
                            ->required(),
                        Forms\Components\TextInput::make('display')
                            ->label('Nombre para mostrar')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->required(),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('uuid', (string) Str::uuid());
                        $set('display', Str::title($state));
                        $set('slug', Str::slug($state));
                    })
                    ->required(),
                Forms\Components\TextInput::make('display')
                    ->label('Nombre para mostrar')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\DatePicker::make('departed_at')
                    ->label('Fecha de salida'),
                Forms\Components\DatePicker::make('arrived_at')
                    ->label('Fecha de vuelta'),
                Forms\Components\TextInput::make('flight_ticket_price')
                    ->hint('grupo')
                    ->label('Precio del billete')
                    ->required()
                    ->live()
                    ->debounce(500)
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = (int) ($get('flight_ticket_price') ?? 0)
                            + (int) ($get('insurance_price') ?? 0)
                            + (int) ($get('accommodation_price') ?? 0)
                            + (int) ($get('transport_price') ?? 0);

                        $set('total_price', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('insurance_price')
                    ->label('Precio del seguro')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = (int) ($get('flight_ticket_price') ?? 0)
                            + (int) ($get('insurance_price') ?? 0)
                            + (int) ($get('accommodation_price') ?? 0)
                            + (int) ($get('transport_price') ?? 0);

                        $set('total_price', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Precio total')
                    ->numeric()
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('city.display')
                    ->sortable(),
                Tables\Columns\TextColumn::make('airline.display')
                    ->sortable(),
                Tables\Columns\TextColumn::make('insurance.display')
                    ->default('N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('display')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departed_at')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('arrived_at')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('flight_ticket_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('insurance_price')
                    ->label('Precio del seguro')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accommodation_stars')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accommodation_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transport_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transport_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
