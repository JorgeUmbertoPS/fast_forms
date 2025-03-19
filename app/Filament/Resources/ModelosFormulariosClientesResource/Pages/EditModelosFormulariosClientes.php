<?php

namespace App\Filament\Resources\ModelosFormulariosClientesResource\Pages;

use App\Filament\Resources\ModelosFormulariosClientesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModelosFormulariosClientes extends EditRecord
{
    protected static string $resource = ModelosFormulariosClientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
