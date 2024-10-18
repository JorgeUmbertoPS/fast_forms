<?php

namespace App\Filament\Resources\LacracaoResource\Pages;

use App\Filament\Resources\LacracaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLacracaos extends ListRecords
{
    protected static string $resource = LacracaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
