<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetResource\Pages;
use App\Filament\Resources\BudgetResource\RelationManagers;
use App\Models\Budget;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'display')
                    ->required(),
                Forms\Components\Select::make('airline_id')
                    ->relationship('airline', 'display')
                    ->required(),
                Forms\Components\Select::make('insurance_id')
                    ->relationship('insurance', 'display'),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('display')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\DatePicker::make('departed_at'),
                Forms\Components\DatePicker::make('arrived_at'),
                Forms\Components\TextInput::make('flight_ticket_price')
                    ->hint('grupo')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('flight_ticket_price') + $get('insurance_price') + $get('accommodation_price') + $get('transport_price');

                        $set('total_price', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('insurance_price')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('flight_ticket_price') + $get('insurance_price') + $get('accommodation_price') + $get('transport_price');

                        $set('total_price', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('accommodation_stars')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('accommodation_price')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('flight_ticket_price') + $get('insurance_price') + $get('accommodation_price') + $get('transport_price');

                        $set('total_price', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('transport_type')
                    ->required(),
                Forms\Components\TextInput::make('transport_price')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?int $old, ?int $state) {
                        $total = $get('flight_ticket_price') + $get('insurance_price') + $get('accommodation_price') + $get('transport_price');

                        $set('total_price', $total);
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('total_price')
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
