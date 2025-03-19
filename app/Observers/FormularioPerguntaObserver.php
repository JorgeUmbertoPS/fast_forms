<?php

namespace App\Observers;

use App\Models\FormularioPergunta;

class FormularioPerguntaObserver
{
    public function creating(FormularioPergunta $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }


}
