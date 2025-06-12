<?php

namespace App\Filament\Resources\ContratoSaasResource\Pages;

use App\Filament\Resources\ContratoSaasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContratoSaas extends ListRecords
{
    protected static string $resource = ContratoSaasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
