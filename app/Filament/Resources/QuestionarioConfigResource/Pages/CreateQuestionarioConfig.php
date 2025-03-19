<?php

namespace App\Filament\Resources\QuestionarioConfigResource\Pages;

use App\Filament\Resources\QuestionarioConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestionarioConfig extends CreateRecord
{
    protected static string $resource = QuestionarioConfigResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return dd($data);
    }
}
