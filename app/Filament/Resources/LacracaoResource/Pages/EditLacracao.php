<?php

namespace App\Filament\Resources\LacracaoResource\Pages;

use App\Filament\Resources\LacracaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLacracao extends EditRecord
{
    protected static string $resource = LacracaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
