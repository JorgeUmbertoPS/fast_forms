<?php

namespace App\Observers;

use App\Models\QuestionarioPerguntaBloco;

class QuestionarioPerguntaBlocoObserver
{
    public function creating(QuestionarioPerguntaBloco $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
