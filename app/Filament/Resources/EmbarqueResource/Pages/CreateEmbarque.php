<?php

namespace App\Filament\Resources\EmbarqueResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EmbarqueResource;

class CreateEmbarque extends CreateRecord
{
    protected static string $resource = EmbarqueResource::class;


    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Embarque Criado')
            ->body('Embarque Criado com Sucesso !!!');
    }






}


