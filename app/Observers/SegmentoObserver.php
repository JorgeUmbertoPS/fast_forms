<?php

namespace App\Observers;

use App\Models\Segmento;

class SegmentoObserver
{
    /**
     * Handle the Segmento "created" event.
     */
    public function creating(Segmento $segmento): void
    {
        $segmento->empresa_id = auth()->user()->empresa_id;
    }

    /**
     * Handle the Segmento "updated" event.
     */
    public function updated(Segmento $segmento): void
    {
        //
    }

    /**
     * Handle the Segmento "deleted" event.
     */
    public function deleted(Segmento $segmento): void
    {
        //
    }

    /**
     * Handle the Segmento "restored" event.
     */
    public function restored(Segmento $segmento): void
    {
        //
    }

    /**
     * Handle the Segmento "force deleted" event.
     */
    public function forceDeleted(Segmento $segmento): void
    {
        //
    }
}
