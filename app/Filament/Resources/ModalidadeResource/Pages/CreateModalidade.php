<?php

namespace App\Filament\Resources\ModalidadeResource\Pages;

use App\Filament\Resources\ModalidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateModalidade extends CreateRecord
{
    protected static string $resource = ModalidadeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Modalidade criada com Sucesso !';
    }
}
