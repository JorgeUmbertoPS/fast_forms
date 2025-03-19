<?php

namespace App\Observers;

use App\Models\ModeloFormularioEmpresa;

class ModeloFormularioEmpresaObserver
{
    public function creating(ModeloFormularioEmpresa $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
