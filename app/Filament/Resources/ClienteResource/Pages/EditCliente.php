<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ClienteResource;

class EditCliente extends EditRecord
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function afterSave(): void
    {

        if ($this->record->logo && Storage::disk('public')->exists($this->record->logo)) {
            $filePath = Storage::disk('public')->path($this->record->logo);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $fileContent = file_get_contents($filePath);
             $this->record->logo_base_64 = 'data:image/' . $extension . ';base64,' . base64_encode($fileContent);
             $this->record->save();
            // (opcional) apagar o arquivo físico
            // Storage::disk('public')->delete($this->record->logo);
            // $this->record->logo = null;
            // $this->record->save();

        }
    }


}
