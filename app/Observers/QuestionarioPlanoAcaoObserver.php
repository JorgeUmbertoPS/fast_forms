<?php

namespace App\Observers;

use App\Models\QuestionarioPlanoAcao;

class QuestionarioPlanoAcaoObserver
{
    public function creating(QuestionarioPlanoAcao $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
