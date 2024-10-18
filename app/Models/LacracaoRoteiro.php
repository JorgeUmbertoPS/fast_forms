<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LacracaoRoteiro extends Model
{
    use HasFactory;

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'lacracoes_roteiros';
    protected $logName      = 'Lacrações - Roteiros';
    
    protected $fillable = [
        'id', 
        'lacracao_id', 
        'descricao', 
        'texto', 
        'sequencia',
        'texto_help',
        'pergunta_dependente_id',
        'pergunta_neutra',
        'pergunta_finaliza_negativa'
        
    ];

    protected  $casts = [
        'pergunta_neutra' => 'boolean',
        'pergunta_finaliza_negativa' => 'boolean'
    ];

}
