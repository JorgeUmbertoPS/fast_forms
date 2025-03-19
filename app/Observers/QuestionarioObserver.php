<?php

namespace App\Observers;

use App\Models\Questionario;

class QuestionarioObserver
{
    public function creating(Questionario $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
