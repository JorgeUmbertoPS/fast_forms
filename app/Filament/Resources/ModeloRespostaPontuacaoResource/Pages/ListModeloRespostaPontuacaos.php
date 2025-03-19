<?php

namespace App\Filament\Resources\ModeloRespostaPontuacaoResource\Pages;

use App\Filament\Resources\ModeloRespostaPontuacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModeloRespostaPontuacaos extends ListRecords
{
    protected static string $resource = ModeloRespostaPontuacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Novo'),
        ];
    }
}
