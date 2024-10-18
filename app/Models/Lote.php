<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitLogs;

class Lote extends Model
{
    use HasFactory;
    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_lotes';
    protected $logName      = 'Lote em Embarque';

    protected $fillable = [
        'id', 
        'embarque_id', 
        'lote'
    ];
}
