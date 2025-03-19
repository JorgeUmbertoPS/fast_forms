<?php

namespace App\Observers;

use App\Models\ModeloFormulario;

class ModeloFormularioObserver
{
    /**
     * Handle the ModeloFormulario "created" event.
     */
    public function creating(ModeloFormulario $modeloFormulario): void
    {
        $modeloFormulario->empresa_id = auth()->user()->empresa_id;
    }

    /**
     * Handle the ModeloFormulario "updated" event.
     */
    public function updated(ModeloFormulario $modeloFormulario): void
    {
        //
    }

    /**
     * Handle the ModeloFormulario "deleted" event.
     */
    public function deleted(ModeloFormulario $modeloFormulario): void
    {
        //
    }

    /**
     * Handle the ModeloFormulario "restored" event.
     */
    public function restored(ModeloFormulario $modeloFormulario): void
    {
        //
    }

    /**
     * Handle the ModeloFormulario "force deleted" event.
     */
    public function forceDeleted(ModeloFormulario $modeloFormulario): void
    {
        //
    }
}
