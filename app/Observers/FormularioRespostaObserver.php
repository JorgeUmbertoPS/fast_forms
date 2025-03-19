<?php

namespace App\Observers;

use App\Models\FormularioResposta;

class FormularioRespostaObserver
{
    public function creating(FormularioResposta $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
