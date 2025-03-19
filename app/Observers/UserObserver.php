<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the CapitalTerceiroBanco "created" event.
     */
    public function creating(User $user): void
    {
        $user->empresa_id = auth()->user()->empresa_id;
    }

   
}
