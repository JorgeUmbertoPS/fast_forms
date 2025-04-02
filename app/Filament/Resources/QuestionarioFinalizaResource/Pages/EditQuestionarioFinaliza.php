<?php

namespace App\Filament\Resources\QuestionarioFinalizaResource\Pages;

use App\Filament\Resources\QuestionarioFinalizaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionarioFinaliza extends EditRecord
{
    protected static string $resource = QuestionarioFinalizaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
