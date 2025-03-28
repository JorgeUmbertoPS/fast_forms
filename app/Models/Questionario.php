<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Observers\QuestionarioObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\Console\Question\Question;

#[ObservedBy([QuestionarioObserver::class])]
class Questionario extends Model
{
    use HasFactory;

    protected $table = "questionarios";

    protected $fillable = [
        'id',
        'form_id',
        'empresa_id',
        'nome',
        'criar_resumo',
        'envia_email_etapas',
        'obriga_assinatura',
        'avisar_dias_antes',
        'data_inicio',
        'data_termino',
        'status',
        'created_at',
        'updated_at',
        'modelo_relatorio_id',
        'titulo',

    ];

    protected $hidden = [
        'empresa_id',
    ];

    protected static function booted(): void
    {
        // SE USUARIO ESTIVER LOGADO
        if(auth()->check()){

            static::addGlobalScope('empresas', function (Builder $query) {
                    $query->where('questionarios.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios.empresa_id', 0);
            });
        }
    }

    public function plano_acoes():hasMany{
        return $this->hasMany(QuestionarioPlanoAcao::class);
    }

    // has many usar o nome perguntas_blocos
    public function questionario_perguntas_blocos()
    {
        return $this->hasMany(QuestionarioPerguntaBloco::class, 'questionario_id', 'id')->with('perguntas_questionario');
    }

    
    public static function total_perguntas($questionario_id){
        $data = QuestionarioPergunta::where('empresa_id', auth()->user()->id)->count();
        return $data;
    }

    public static function total_perguntas_respondidas($questionario_id){
       $data = QuestionarioPergunta::where('status', 1)
                                    ->where('empresa_id', auth()->user()->id)
                                   // ->where('id')
                                    ->count();
        return $data;                                    
    }

    public static function total_perguntas_nao_respondidas($questionario_id){
        $data = QuestionarioPergunta::where('status', 0)
                                    ->where('empresa_id', auth()->user()->id)
                                    ->count();
        return $data;                                    
    }    


    public static function AtualizarQuestionario($id){

        try{
            DB::beginTransaction();
            //seleciona as perguntas do bloco de perguntas do form_id
            $blocos_perguntas = FormularioPerguntaBloco::where('form_id', $id)->get();

            //seleciona os blocos dos questionarios
            $blocos_questionarios = QuestionarioPerguntaBloco::where('form_id', $id)->get();

            //se o bloco de perguntas for maior que bloco de questionarios
            // faz um foreach para inserir os blocos de questionarios quw nao existem no bloco de perguntas
            if(count($blocos_perguntas) > count($blocos_questionarios)){

                foreach($blocos_perguntas as $bloco){

                    $qst_pergunta_bloco = QuestionarioPerguntaBloco::where('form_bloco_id', $bloco->id)->first();

                    if(!$qst_pergunta_bloco){

                        $qst_pergunta_bloco = new QuestionarioPerguntaBloco();
                        $qst_pergunta_bloco->questionario_id        = $id;
                        $qst_pergunta_bloco->empresa_id             = $bloco->empresa_id;
                        $qst_pergunta_bloco->ordem                  = $bloco->ordem;
                        $qst_pergunta_bloco->created_at             = Date('Y-m-d H:i:s');
                        $qst_pergunta_bloco->updated_at             = Date('Y-m-d H:i:s');
                        $qst_pergunta_bloco->form_bloco_id          = $bloco->id;
                        $qst_pergunta_bloco->form_id                = $bloco->form_id;
                        $qst_pergunta_bloco->descricao              = $bloco->descricao;
                        $qst_pergunta_bloco->save();           
                    }
        
                }
            }

            //seleciona perguntas dos formularios
            $perguntas = FormularioPergunta::where('form_id', $id)->get();
            
            // faz um foreach nas perguntas e pesquisa nas perguntas dos questionarios. Se nao existir insere
            foreach($perguntas as $pergunta){

                $qst_pergunta = QuestionarioPergunta::where('form_pergunta_id', $pergunta->id)->first();

                if(!$qst_pergunta){

                    $qst_pergunta                   = new QuestionarioPergunta();
                    $qst_pergunta->pergunta_nome    = $pergunta->nome;
                    $qst_pergunta->pergunta_id      = $pergunta->id;
                    $qst_pergunta->form_id          = $pergunta->form_id;
                    $qst_pergunta->resposta_tipo_id = $pergunta->resposta_tipo_id;
                    $qst_pergunta->empresa_id       = $pergunta->empresa_id;
                    $qst_pergunta->bloco_id         = $pergunta->bloco_id;
                    $qst_pergunta->user_id          = auth()->user()->id;
                    $qst_pergunta->ordem            = $pergunta->ordem;
                    $qst_pergunta->questionario_id  = $id;
                    $qst_pergunta->id_mascara       = $pergunta->id_mascara;
                    $qst_pergunta->save();
                }
            }





            DB::commit();
            return array('status' => true, 'mensagem' => 'Questionário Atuliazado com sucesso');
        }
        catch(\Exception $e){
            DB::rollBack();
            return array('status' => false, 'mensagem' => $e->getMessage());
        }

    }


    public static function DownloadPdf($id)
    {
        // Recupera os dados do questionário
        $dados_quesionario = Questionario::find($id);
        $blocos_perguntas = QuestionarioPerguntaBloco::where('questionario_id', $id)
            ->with('perguntas_questionario')
            ->get(); 
        $empresa = Empresa::find($dados_quesionario->empresa_id);

        $questionario = [
            'nome' => $dados_quesionario->titulo,
            'relatorio' => $dados_quesionario->relatorio->nome,
            'blocos' => $blocos_perguntas,
            'empresa' => $empresa,
        ];

        // Gera o PDF com a view e os dados passados
        $pdf = PDF::loadView('questionarios.gerar-pdf', [
            'questionario' => $questionario,
        ])->setPaper('A4', 'portrait');

        return $pdf;  // Retorna o objeto PDF
    }
    

    public static function FinalizarQuestionario($id){

        try{
            DB::beginTransaction();
            $questionario = Questionario::where('id', $id)->first();
            $questionario->status = 0;
            $questionario->save();
            DB::commit();
            return array('status' => true, 'mensagem' => 'Questionário finalizado com sucesso');
        }
        catch(\Exception $e){
            DB::rollBack();
            return array('status' => false, 'mensagem' => $e->getMessage());
        }

    }

    // relatorio
    public function relatorio():hasOne{
        return $this->hasOne(ModeloRelatorio::class, 'id', 'modelo_relatorio_id');
    }

}
