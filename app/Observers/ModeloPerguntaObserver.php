<?php

namespace App\Observers;

use App\Models\ModeloPergunta;

class ModeloPerguntaObserver
{


    public function creating(ModeloPergunta $ModeloPergunta): void
    {
        $ModeloPergunta->empresa_id = auth()->user()->empresa_id;
    }


    /**
     * Handle the ModeloPerguntaBloco "updated" event.
     */
    public function updated(ModeloPergunta $ModeloPergunta): void
    {
        //
    }

    /**
     * Handle the ModeloPergunta "deleted" event.
     */
    public function deleted(ModeloPergunta $ModeloPergunta): void
    {
        //
    }

    /**
     * Handle the ModeloPergunta "restored" event.
     */
    public function restored(ModeloPergunta $ModeloPergunta): void
    {
        //
    }

    /**
     * Handle the ModeloPergunta "force deleted" event.
     */
    public function forceDeleted(ModeloPergunta $ModeloPergunta): void
    {
        //
    }
}
