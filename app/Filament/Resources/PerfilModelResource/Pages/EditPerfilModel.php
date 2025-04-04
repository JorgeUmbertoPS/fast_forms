<?php

namespace App\Filament\Resources\PerfilModelResource\Pages;

use App\Filament\Resources\PerfilModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerfilModel extends EditRecord
{
    protected static string $resource = PerfilModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
