<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\LogsActivityContext;

trait TraitLogs {

    use LogsActivity;
    
    public function getDescriptionForEvent(string $eventName):string
    {
        $ev = ['deleted' => 'excluído', 'updated' => 'alterado', 'created' => 'inserido'];
        return "O registro foi {$ev[$eventName]} por " . Auth::user()->name;
    }
 
    public function tapActivity(Activity $activity, string $eventName)
    {
         $activity->empresa_id = Auth::user()->empresa_id;
    }
 
    public function getActivitylogOptions(): LogOptions
    {
        if(!isset($this->modelName)){
            throw new Exception('É preciso criar a váriavel $modelName no Model. ($modelName = "Nome do Log";)');
        }
        return LogOptions::defaults()
                ->logFillable()
                ->setDescriptionForEvent(fn(string $eventName) => "O registro foi {$eventName}")
                ->useLogName($this->logName);
    }
}
