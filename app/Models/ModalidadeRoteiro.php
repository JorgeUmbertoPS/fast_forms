<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitLogs;

class ModalidadeRoteiro extends Model
{
    use HasFactory;

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'modalidades_roteiros';
    protected $logName      = 'Roteiros de Modalidades';
    
    protected $fillable = [
        'id', 'modalidade_id', 'sequencia', 'descricao', 'texto', 'texto_help', 
        'pergunta_dependente_id', 'pergunta_neutra', 'pergunta_finaliza_negativa'
    ];

    protected  $casts = [
        'pergunta_neutra' => 'boolean',
        'pergunta_finaliza_negativa' => 'boolean'
    ];
}
