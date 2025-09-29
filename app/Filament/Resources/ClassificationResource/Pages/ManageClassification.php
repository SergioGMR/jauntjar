<?php

namespace App\Filament\Resources\ClassificationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ClassificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageClassification extends ManageRecords
{
    protected static string $resource = ClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
