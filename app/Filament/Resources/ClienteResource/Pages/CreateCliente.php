<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ClienteResource;

class CreateCliente extends CreateRecord
{
    protected static string $resource = ClienteResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->logo && Storage::disk('public')->exists($this->record->logo)) {
            $filePath = Storage::disk('public')->path($this->record->logo);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $fileContent = file_get_contents($filePath);

            $this->record->update([
                'logo_base_64' => 'data:image/' . $extension . ';base64,' . base64_encode($fileContent),
            ]);
        }
    }
}
