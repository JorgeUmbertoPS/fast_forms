<?php

namespace App\Observers;

use App\Models\ModeloResposta;

class ModeloRespostaObserver
{


    public function creating(ModeloResposta $ModeloResposta): void
    {
        $ModeloResposta->empresa_id = auth()->user()->empresa_id;
    }


    /**
     * Handle the ModeloRespostaBloco "updated" event.
     */
    public function updated(ModeloResposta $ModeloResposta): void
    {
        //
    }

    /**
     * Handle the ModeloResposta "deleted" event.
     */
    public function deleted(ModeloResposta $ModeloResposta): void
    {
        //
    }

    /**
     * Handle the ModeloResposta "restored" event.
     */
    public function restored(ModeloResposta $ModeloResposta): void
    {
        //
    }

    /**
     * Handle the ModeloResposta "force deleted" event.
     */
    public function forceDeleted(ModeloResposta $ModeloResposta): void
    {
        //
    }
}
