<?php

namespace App\Filament\Resources\ModeloFormularioResource\Pages;

use App\Filament\Resources\ModeloFormularioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeloFormulario extends EditRecord
{
    protected static string $resource = ModeloFormularioResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
       // dd($data);
        return $data;
    }

  


}
