<?php

namespace App\Filament\Resources\QuestionarioResource\Pages;

use App\Filament\Resources\QuestionarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionario extends EditRecord
{
    protected static string $resource = QuestionarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }



}
