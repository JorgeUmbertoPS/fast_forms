<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\QuestionarioPerguntaObserver;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
#[ObservedBy([QuestionarioPerguntaObserver::class])]
class QuestionarioPergunta extends Model
{
    use HasFactory;
    protected $table = "questionarios_perguntas";

    protected $fillable = [
        'pergunta_id',
        'pergunta_nome',
        'empresa_id',
        'resposta_tipo_id',
        'bloco_id',
        'data_termino',
        'user_id',
        'resposta',
        'status',
        'created_at',
        'updated_at',
        'data_resposta',
        'ordem',
        'pergunta_tipo',
        'pergunta_formulario_id',
        'form_id',
        'resposta_valor_tipo',
        'obriga_justificativa',
        'obriga_midia',

        
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_perguntas.empresa_id', auth()->user()->empresa_id);
        });
    }


    //questionario_respostas
    public function questionario_respostas():hasMany{
        return $this->hasMany(QuestionarioResposta::class, 'pergunta_id', 'id');
    }


    public function perguntasImagens():HasMany{
        return $this->hasMany(QuestionarioPerguntaMidia::class, 'id');
    }

    public static function finalizaPergunta($record){
        
        if($record > 0){
            DB::table('questionarios_perguntas')->where('id', $record)
                    ->update([
                        'status'        => 0,
                        'data_resposta' => Date('Y-m-d H:i:s'),
                        'user_id'       => auth()->user()->id
                    ]);
                    
            return array('status' => true, 'mensagem' => '');
        }
        else        
            return array('status' => false, 'mensagem' => 'Problema nos dados');
    }
}
