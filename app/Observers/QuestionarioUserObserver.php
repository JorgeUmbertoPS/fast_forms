<?php

namespace App\Observers;

use App\Models\QuestionarioUser;

class QuestionarioUserObserver
{
    public function creating(QuestionarioUser $obj): void
    {
        $obj->empresa_id = auth()->user()->empresa_id;
    }
}
