<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;
    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'clientes';
    protected $logName      = 'Clientes';

    protected $fillable = [
        'id', 'nome', 'created_at', 'updated_at', 'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];




}