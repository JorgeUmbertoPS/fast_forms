<?php

namespace App\Filament\Resources\EmbarqueContainerFotoResource\Pages;

use App\Filament\Resources\EmbarqueContainerFotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmbarqueContainerFotos extends ListRecords
{
    protected static string $resource = EmbarqueContainerFotoResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $title = 'Banco de Imagens';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
