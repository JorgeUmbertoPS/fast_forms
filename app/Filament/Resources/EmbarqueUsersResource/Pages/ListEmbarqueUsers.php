<?php

namespace App\Filament\Resources\EmbarqueUsersResource\Pages;

use App\Filament\Resources\EmbarqueUsersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmbarqueUsers extends ListRecords
{
    protected static string $resource = EmbarqueUsersResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
