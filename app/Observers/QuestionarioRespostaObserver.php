<?php

namespace App\Observers;

use App\Models\QuestionarioResposta;

class QuestionarioRespostaObserver
{
    public function creating(QuestionarioResposta $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
