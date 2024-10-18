<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'setores';
    protected $logName      = 'Setores';

    protected $fillable = [
        'id', 
        'nome', 
        'created_at',
        'updated_at'
    ];
}
