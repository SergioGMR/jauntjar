<?php

namespace App\Filament\Resources\InsuranceResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\InsuranceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInsurances extends ManageRecords
{
    protected static string $resource = InsuranceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
