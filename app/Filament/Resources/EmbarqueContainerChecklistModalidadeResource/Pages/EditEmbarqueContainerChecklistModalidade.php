<?php
namespace App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource\Pages;



use App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmbarqueContainerChecklistModalidade extends EditRecord
{
    protected static string $resource = EmbarqueContainerChecklistModalidadeResource::class;

    protected static ?string $title = 'Alterar Foto';

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
