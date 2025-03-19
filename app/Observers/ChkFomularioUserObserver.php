<?php

namespace App\Observers;

use App\Models\ChkFomularioUser;

class ChkFomularioUserObserver
{
    public function creating(ChkFomularioUser $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }

}
