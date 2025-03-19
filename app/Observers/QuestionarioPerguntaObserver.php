<?php

namespace App\Observers;

use App\Models\QuestionarioPergunta;

class QuestionarioPerguntaObserver
{
    public function creating(QuestionarioPergunta $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
