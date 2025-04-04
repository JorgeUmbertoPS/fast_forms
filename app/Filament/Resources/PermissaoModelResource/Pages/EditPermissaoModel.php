<?php

namespace App\Filament\Resources\PermissaoModelResource\Pages;

use App\Filament\Resources\PermissaoModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermissaoModel extends EditRecord
{
    protected static string $resource = PermissaoModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
