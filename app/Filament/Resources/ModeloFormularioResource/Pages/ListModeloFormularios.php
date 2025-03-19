<?php

namespace App\Filament\Resources\ModeloFormularioResource\Pages;

use App\Filament\Resources\ModeloFormularioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModeloFormularios extends ListRecords
{
    protected static string $resource = ModeloFormularioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Novo'),
        ];
    }

}
