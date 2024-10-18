<?php

namespace App\Filament\Resources\EmbarqueContainerChecklistRespostaResource\Pages;

use App\Filament\Resources\EmbarqueContainerChecklistRespostaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmbarqueContainerChecklistResposta extends EditRecord
{
    protected static string $resource = EmbarqueContainerChecklistRespostaResource::class;

    protected static ?string $title = 'Alterar Foto do Checklist';

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->visible(function (): bool {
                return false;
            });
    }


    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->visible(function (): bool {
                return true;
            })->label('Voltar');
    }
}
