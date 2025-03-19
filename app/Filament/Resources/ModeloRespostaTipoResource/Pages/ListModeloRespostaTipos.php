<?php

namespace App\Filament\Resources\ModeloRespostaTipoResource\Pages;

use App\Filament\Resources\ModeloRespostaTipoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModeloRespostaTipos extends ListRecords
{
    protected static string $resource = ModeloRespostaTipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Novo'),
        ];
    }
}
