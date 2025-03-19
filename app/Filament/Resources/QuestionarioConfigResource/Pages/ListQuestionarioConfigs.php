<?php

namespace App\Filament\Resources\QuestionarioConfigResource\Pages;

use App\Filament\Resources\QuestionarioConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestionarioConfigs extends ListRecords
{
    protected static string $resource = QuestionarioConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
