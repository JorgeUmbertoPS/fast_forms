<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\QuestionarioRespostaObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Symfony\Component\Console\Question\Question;

#[ObservedBy([QuestionarioRespostaObserver::class])]
class QuestionarioResposta extends Model
{
    use HasFactory;

    protected $table = "questionarios_respostas";

    protected $fillable = [
        'id',
        'pergunta_id',
        'empresa_id',
        'pontuacao_id',
        'obriga_justificativa',
        'justificativa',
        'obriga_midia',
        'status',
        'resposta',
        'ordem',
        'created_at',
        'updated_at',
        'form_id',
        'nome',
        'questionario_id',
        'bloco_id', 
        'icon',
        
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_respostas.empresa_id', auth()->user()->empresa_id);
        });
    }

    //respostasQuestionario
    public function respostas_questionarios(){
        return $this->hasMany(QuestionarioResposta::class);
    }

    public static function Responder($record, $state = null){
        try{
            $pergunta = QuestionarioPergunta::find($record['pergunta_id']);
   
            if($pergunta->resposta_tipo_id == ModeloRespostaTipo::RESPOSTA_TIPO_ALTERNATIVA){
                
                QuestionarioResposta::where('pergunta_id', $record['pergunta_id'])->update(['resposta' => null]);
                
                $resp = QuestionarioResposta::where('id', $record['id'])->first();
                $resp['resposta'] = $record['nome'];
                $resp->save();
            }

            if($pergunta->resposta_tipo_id == ModeloRespostaTipo::RESPOSTA_TIPO_MULTIPLA_ESCOLHA){
                
                $resp = QuestionarioResposta::where('id', $record['id'])->first();

                if($resp['resposta'] == $record['nome']){
                    $resp['resposta'] = null;
                }else{
                    $resp['resposta'] = $record['nome'];
                }
                $resp->save();
            }

            if($pergunta->resposta_tipo_id == ModeloRespostaTipo::RESPOSTA_TIPO_VALOR){

                $resp = QuestionarioResposta::where('id', $record['id'])->first();
                dd($resp, $record, $state);
                $resp['resposta'] = $record['resposta'];
                $resp->save();
            }

            return ['return'=> true, 'mesnagem'=> 'Resposta salva com sucesso', 'erro' => []];
        }
        catch(\Throwable $th){
            return ['return'=> false, 'mesnagem'=> 'Erro ao salvar resposta', 'erro' => $th->getMessage()];
        }



        //dd($pergunta, $record);
    }





    
}
