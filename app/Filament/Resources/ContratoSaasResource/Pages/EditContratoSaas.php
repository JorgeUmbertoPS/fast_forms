<?php

namespace App\Filament\Resources\ContratoSaasResource\Pages;

use App\Filament\Resources\ContratoSaasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContratoSaas extends EditRecord
{
    protected static string $resource = ContratoSaasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
