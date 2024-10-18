<?php

namespace App\Filament\Resources\EmbarqueResource\Pages;

use App\Filament\Resources\EmbarqueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmbarques extends ListRecords
{
    protected static string $resource = EmbarqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
