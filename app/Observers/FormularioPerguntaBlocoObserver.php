<?php

namespace App\Observers;

use App\Models\FormularioPerguntaBloco;

class FormularioPerguntaBlocoObserver
{
    public function creating(FormularioPerguntaBloco $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }


}
