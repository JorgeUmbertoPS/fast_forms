<?php

namespace App\Observers;

use App\Models\EmpresaUsuario;

class EmpresaUserObserver
{
    public function creating(EmpresaUsuario $empresaUsuario): void
    {
        $empresaUsuario->empresa_id = auth()->user()->empresa_id;
    }
}
