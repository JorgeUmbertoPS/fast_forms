<?php

namespace App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource\Pages;

use App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmbarqueContainerLacracaoResposta extends EditRecord
{
    protected static string $resource = EmbarqueContainerLacracaoRespostaResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
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
