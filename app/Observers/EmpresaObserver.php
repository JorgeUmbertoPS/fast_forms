<?php

namespace App\Observers;

use App\Models\Empresa;

class EmpresaObserver
{
    /**
     * Handle the Empresa "created" event.
     */
    public function creating(Empresa $empresa): void
    {
        $empresa->id = auth()->user()->empresa_id;
    }

    /**
     * Handle the Empresa "updated" event.
     */
    public function updated(Empresa $empresa): void
    {
//
    }

    /**
     * Handle the Empresa "deleted" event.
     */
    public function deleted(Empresa $empresa): void
    {
//
    }

    /**
     * Handle the Empresa "restored" event.
     */
    public function restored(Empresa $empresa): void
    {
        //
    }

    /**
     * Handle the Empresa "force deleted" event.
     */
    public function forceDeleted(Empresa $empresa): void
    {
        //
    }
}
