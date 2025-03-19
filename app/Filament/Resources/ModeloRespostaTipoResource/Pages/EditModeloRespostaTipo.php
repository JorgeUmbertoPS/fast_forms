<?php

namespace App\Filament\Resources\ModeloRespostaTipoResource\Pages;

use App\Filament\Resources\ModeloRespostaTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeloRespostaTipo extends EditRecord
{
    protected static string $resource = ModeloRespostaTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
