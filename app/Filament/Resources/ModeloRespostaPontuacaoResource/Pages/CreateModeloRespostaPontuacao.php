<?php

namespace App\Filament\Resources\ModeloRespostaPontuacaoResource\Pages;

use App\Filament\Resources\ModeloRespostaPontuacaoResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateModeloRespostaPontuacao extends CreateRecord
{
    protected static string $resource = ModeloRespostaPontuacaoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::find(auth()->id());
        $data['empresa_id'] = $user->empresa_id;
        return $data;
    }
}
