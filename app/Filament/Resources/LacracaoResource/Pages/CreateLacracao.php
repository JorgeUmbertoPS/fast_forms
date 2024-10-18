<?php

namespace App\Filament\Resources\LacracaoResource\Pages;

use App\Filament\Resources\LacracaoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLacracao extends CreateRecord
{
    protected static string $resource = LacracaoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
