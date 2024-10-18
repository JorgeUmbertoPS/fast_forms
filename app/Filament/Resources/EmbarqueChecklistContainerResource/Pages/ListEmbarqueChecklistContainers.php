<?php

namespace App\Filament\Resources\EmbarqueChecklistContainerResource\Pages;

use App\Filament\Resources\EmbarqueChecklistContainerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmbarqueChecklistContainers extends ListRecords
{
    protected static string $resource = EmbarqueChecklistContainerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
