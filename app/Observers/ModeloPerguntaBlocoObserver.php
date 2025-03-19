<?php

namespace App\Observers;

use App\Models\ModeloPerguntaBloco;

class ModeloPerguntaBlocoObserver
{


    public function creating(ModeloPerguntaBloco $modeloFormulario): void
    {
        $modeloFormulario->empresa_id = auth()->user()->empresa_id;
    }


    /**
     * Handle the ModeloPerguntaBloco "updated" event.
     */
    public function updated(ModeloPerguntaBloco $modeloPerguntaBloco): void
    {
        //
    }

    /**
     * Handle the ModeloPerguntaBloco "deleted" event.
     */
    public function deleted(ModeloPerguntaBloco $modeloPerguntaBloco): void
    {
        //
    }

    /**
     * Handle the ModeloPerguntaBloco "restored" event.
     */
    public function restored(ModeloPerguntaBloco $modeloPerguntaBloco): void
    {
        //
    }

    /**
     * Handle the ModeloPerguntaBloco "force deleted" event.
     */
    public function forceDeleted(ModeloPerguntaBloco $modeloPerguntaBloco): void
    {
        //
    }
}
