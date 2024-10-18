<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitLogs;

class Transportadora extends Model
{

    use HasFactory;
    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'transportadoras';
    protected $logName      = 'Transportadoras';

    protected $fillable = [
        'id', 'nome', 'created_at', 'updated_at'
    ];
}
