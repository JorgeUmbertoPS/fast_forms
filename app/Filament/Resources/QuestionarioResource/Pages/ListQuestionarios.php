<?php

namespace App\Filament\Resources\QuestionarioResource\Pages;

use App\Filament\Resources\QuestionarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestionarios extends ListRecords
{
    protected static string $resource = QuestionarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
