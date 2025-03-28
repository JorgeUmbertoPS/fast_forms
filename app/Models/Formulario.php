<?php

namespace App\Models;

use App\Models\ChkFomularioUser;
use App\Models\QuestionarioResposta;
use Illuminate\Support\Facades\Auth;
use App\Observers\FormularioObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DB;
#[ObservedBy([FormularioObserver::class])]
class Formulario extends Model
{
    use HasFactory;

    protected $table = "formularios";

    protected $fillable = [
        'id',
        'modelo_id',
        'empresa_id',
        'criar_resumo',
        'envia_email_etapas',
        'obriga_assinatura',
        'avisar_dias_antes',
        'data_inicio',
        'data_termino',
        'status',
        'created_at',
        'updated_at',
        'nome',
        'gerar_plano_acao',


    ];

    protected $casts = [
        'status' => 'bool'
    ];

    public function modelo():HasOne{
        return $this->hasOne(ModeloFormulario::class, 'id', 'modelo_id');
    }

    public function segmento():BelongsTo {
        return $this->BelongsTo(Segmento::class);
    }

    public function perguntas():HasMany{
        return $this->hasMany(FormularioPergunta::class, 'form_id');
    }

    public function bloco_pergunta(): HasOne
    {
        return $this->hasOne(FormularioPerguntaBloco::class, 'form_id');
    }

    public function FormulariosUsers():BelongsToMany{
        return ($this->BelongsToMany(User::class, 'formularios_users', 'form_id'));
    }
    
    public static function GerarQuestionario($formulario){

        try {
            DB::beginTransaction();
            $questionario                               = new Questionario();            
            $questionario->form_id                      = $formulario['id'];
            $questionario->empresa_id                   = $formulario['empresa_id'];
            $questionario->nome                         = $formulario['nome'];
            $questionario->envia_email_etapas           = $formulario['envia_email_etapas'];
            $questionario->obriga_assinatura            = $formulario['obriga_assinatura'];
            $questionario->avisar_dias_antes            = $formulario['avisar_dias_antes'];;
            $questionario->data_inicio                  = Date('Y-m-d H:i:s');
            $questionario->data_termino                 = Date('Y-m-d H:i:s');
            $questionario->created_at                   = Date('Y-m-d H:i:s');
            $questionario->updated_at                   = Date('Y-m-d H:i:s');
            $questionario->status                       = $formulario['status'];
            $questionario->save();

             $blocos = FormularioPerguntaBloco::where('form_id', $formulario['id'])->get();
            
            foreach($blocos as $bloco){

                $qst_pergunta_bloco                         = new QuestionarioPerguntaBloco();
                $qst_pergunta_bloco->titulo                 = $bloco->titulo;
                $qst_pergunta_bloco->sub_titulo             = $bloco->sub_titulo;
                $qst_pergunta_bloco->descricao              = $bloco->descricao;
                $qst_pergunta_bloco->questionario_id        = $questionario->id;
                $qst_pergunta_bloco->empresa_id             = $bloco->empresa_id;
                $qst_pergunta_bloco->ordem                  = $bloco->ordem;
                $qst_pergunta_bloco->created_at             = Date('Y-m-d H:i:s');
                $qst_pergunta_bloco->updated_at             = Date('Y-m-d H:i:s');
                $qst_pergunta_bloco->form_bloco_id          = $bloco->id;
                $qst_pergunta_bloco->form_id                = $formulario['id'];
                $qst_pergunta_bloco->instrucoes             = $bloco->instrucoes;
                $qst_pergunta_bloco->observacoes            = $bloco->observacoes;
                $qst_pergunta_bloco->icon                   = $bloco->icon;
                $qst_pergunta_bloco->save();           

                $perguntas = FormularioPergunta::where('bloco_id', $bloco->id)->get();

                foreach($perguntas as $pergunta){

                    $qst_pergunta                           = new QuestionarioPergunta();
                    $qst_pergunta->pergunta_nome            = $pergunta->nome;
                    $qst_pergunta->pergunta_id              = $pergunta->id;
                    $qst_pergunta->form_id                  = $formulario['id'];
                    $qst_pergunta->resposta_tipo_id         = $pergunta->resposta_tipo_id;
                    $qst_pergunta->empresa_id               = $bloco->empresa_id;
                    $qst_pergunta->bloco_id                 = $qst_pergunta_bloco->id;
                    $qst_pergunta->user_id                  = Auth::user()->id;
                    $qst_pergunta->ordem                    = $pergunta->ordem;
                    $qst_pergunta->questionario_id          = $questionario->id;
                    $qst_pergunta->id_mascara               = $pergunta->id_mascara;
                    $qst_pergunta->created_at               = Date('Y-m-d H:i:s');
                    $qst_pergunta->updated_at               = Date('Y-m-d H:i:s');
                    $qst_pergunta->pontuacao_id             = $pergunta->pontuacao_id;
                    $qst_pergunta->obriga_justificativa     = $pergunta->obriga_justificativa;
                    $qst_pergunta->justificativa            = $pergunta->justificativa;
                    $qst_pergunta->obriga_midia             = $pergunta->obriga_midia;
                    $qst_pergunta->resposta                 = null;
                    $qst_pergunta->save();
               
                    $respostas = FormularioResposta::where('pergunta_id', $pergunta->id)->get();

                    foreach($respostas as $resposta){
                        $qst_resposta                       = new QuestionarioResposta();
                        $qst_resposta->nome                 = $resposta->nome;
                        $qst_resposta->pergunta_id          = $qst_pergunta->id;
                        $qst_resposta->empresa_id           = $resposta->empresa_id;                        
                        $qst_resposta->created_at           = Date('Y-m-d H:i:s');
                        $qst_resposta->updated_at           = Date('Y-m-d H:i:s');
                        $qst_resposta->icon                 = $resposta->icon;
                        $qst_resposta->save();
                    }
                }  
            }          

            DB::commit();

            return array('status' => true, 'mensagem' => '');

        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100));
        }          


    }

    protected static function booted(): void
    {
        if(auth()->check() && auth()->user()->empresa_id != null){
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('formularios.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('formularios.empresa_id', null);
            });
        }

    } 

    public function desativar($record) {
        $registro = Formulario::find($record);
        if($registro->status == 1)
            $registro->status = 0;
        else
            $registro->status = 1;
        $registro->save();
    }

    public static function GetIcons(){

        return array(
            'heroicon-m-trash' => 'Lixeira',
        );
    }



}
