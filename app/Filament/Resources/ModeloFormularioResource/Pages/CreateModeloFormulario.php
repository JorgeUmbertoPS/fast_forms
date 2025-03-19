<?php

namespace App\Filament\Resources\ModeloFormularioResource\Pages;

use App\Filament\Resources\ModeloFormularioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;


class CreateModeloFormulario extends CreateRecord
{
    protected static string $resource = ModeloFormularioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::find(auth()->id());

        $data['user_id'] = $user->id;
        $data['empresa_id'] = $user->empresa_id;
        return $data;
    }







}

