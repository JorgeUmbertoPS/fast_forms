<?php

namespace App\Filament\Resources\ModeloRespostaPontuacaoResource\Pages;

use App\Filament\Resources\ModeloRespostaPontuacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeloRespostaPontuacao extends EditRecord
{
    protected static string $resource = ModeloRespostaPontuacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
