<?php

namespace App\Filament\Resources\EmbarqueChecklistContainerResource\Pages;


use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EmbarqueChecklistContainerResource;

class EditEmbarqueChecklistContainer extends EditRecord
{
    protected static string $resource = EmbarqueChecklistContainerResource::class;

    protected static ?string $title = 'Checklist Container';

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
                return false;
            });
    }



}
