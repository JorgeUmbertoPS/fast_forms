<?php

namespace App\Filament\Resources\ParametroSistemaResource\Pages;

use App\Filament\Resources\ParametroSistemaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParametroSistemas extends ListRecords
{
    protected static string $resource = ParametroSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
