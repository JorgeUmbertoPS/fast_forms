<?php

namespace App\Observers;

use App\Models\Formulario;

class FormularioObserver
{
    public function creating(Formulario $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
