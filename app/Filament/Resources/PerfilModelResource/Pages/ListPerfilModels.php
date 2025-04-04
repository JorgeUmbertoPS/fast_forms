<?php

namespace App\Filament\Resources\PerfilModelResource\Pages;

use App\Filament\Resources\PerfilModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerfilModels extends ListRecords
{
    protected static string $resource = PerfilModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
