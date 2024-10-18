<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oic extends Model
{
    use HasFactory;
    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_oics';
    protected $logName      = 'OIC em Checklist';

    protected $fillable = [
        'id', 
        'embarque_id', 
        'oic'
    ];
}
