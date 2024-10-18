<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

//use softdelete
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionarioPerguntas extends Model
{
    use HasFactory;

    use TraitLogs, SoftDeletes;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'questionarios_perguntas';
    protected $logName      = 'Perguntas de Questionários';
    
    
    protected $fillable = [
        'id', 'questionario_id', 'pergunta', 'texto', 'texto_help', 'pergunta_dependente_id', 
                'pergunta_neutra', 'sequencia', 'pergunta_imagem', 'created_at', 'updated_at',
                'pergunta_finaliza_negativa'
    ];

    protected  $casts = [
        'pergunta_neutra' => 'boolean',
        'pergunta_finaliza_negativa' => 'boolean'
    ];

    //obterPerguntasDependentes
    public static function obterPerguntasDependentes($record){

        if($record != null){
           return (QuestionarioPerguntas::where('questionario_id', $record['questionario_id'])
                        ->where('pergunta_imagem', 'P')
                        ->whereNot('id', $record['id'])
                        ->orderBy('id')
                        ->pluck('pergunta', 'id'));
        }else{

            return null;
        }
            
        
    }


}
