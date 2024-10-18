<?php

namespace App\Filament\Resources\EmbarqueResource\Pages;

use Filament\Actions;
use App\Models\Embarque;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EmbarqueResource;

class EditEmbarque extends EditRecord
{
    protected static string $resource = EmbarqueResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }



    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->visible(function ($record): bool {
                return Embarque::PodeAtualizarEmbarqueFinalizado($record); ;
            });
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
        ->label('Voltar');
    }


}
