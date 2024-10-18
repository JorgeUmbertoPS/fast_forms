<?php

namespace App\Filament\Resources\QuestionarioResource\Pages;

use App\Filament\Resources\QuestionarioResource;
use App\Models\QuestionarioPerguntas;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestionario extends CreateRecord
{
    protected static string $resource = QuestionarioResource::class;

    // incrementar a sequencia de perguntas. Utilizar o mutate



}
