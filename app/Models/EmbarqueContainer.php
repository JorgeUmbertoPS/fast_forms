<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmbarqueContainer extends Model
{
    use HasFactory;
    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_containers';
    protected $logName      = 'Embarques de Container';

    // constantes de status 0 - Em avaliação, 1 - Aprovado, 2 - Reprovado'
    const STATUS_AVALIACAO = 0;
    const STATUS_APROVADO = 1;
    const STATUS_REPROVADO = 2;

    protected $fillable = [
        'id',
        'embarque_id',
        'questionario_id',
        'container',  
        'lacracao_id' ,
        'finalizado',  
        'user_id_questionario',
        'user_id_modalidade',
        'user_id_lacres',
        'data_fechamento_questionario',
        'data_fechamento_modalidade',
        'data_fechamento_lacres',
        'status',
        'oic',
        'lotes'

    ];

    
    public function modalidade(){
        return $this->BelongsToMany(Modalidade::class, 'embarques_containers_modalidades', 'container_id');
    }

    public function questionarios_containers(){
        return $this->belongsTo(Questionario::class);
    }

    public function questionarios(){
        return $this->hasMany(EmbarqueContainerChecklistResposta::class, 'embarques_containers_id');
    }

    public function modalidades(){
        return $this->hasMany(EmbarqueContainerModalidadeResposta::class, 'embarques_containers_id');
    }

    public function lacres(){
        return $this->hasMany(EmbarqueContainerLacracaoResposta::class, 'embarques_containers_id');
    }

    // usuario que fechou o questionario
    public function user_questionario(){
        return $this->hasOne(User::class, 'id', 'user_id_questionario');
    }

    // usuario que fechou a modalidade
    public function user_modalidade(){
        return $this->hasOne(User::class, 'id', 'user_id_modalidade');
    }

    // usuario que fechou os lacres
    public function user_lacres(){
        return $this->hasOne(User::class, 'id', 'user_id_lacres');
    }
    
    public static function total_incosistencias($id){
        return 1;
    }

    public static function FinalizaContainer($container_id){

        try {
      
            // preciso verificar se todos os questionários do container estão finalizados
            $embarque = EmbarqueContainer::find($container_id);

            $msg = '';
            // se todos os questionários, lacres e modalidades estiverem finalizados, finaliza o container

            $container = EmbarqueContainer::find($container_id);
            $container->finalizado = 1;
            $container->save();
            // criar mensagem de retorno para o usuário que os conteiners foram finalizados
            $msg = 'Container finalizado com sucesso!';

            // tentar finalizar o embarque
            // verificar se todos os containers do embarque estão finalizados
            $containers = EmbarqueContainer::where('embarque_id', $container->embarque_id)->where('finalizado', 0)->get();
  
            // se todos os containers estiverem finalizados, finaliza o embarque
            if($containers->count() == 0){
                $embarque = Embarque::find($container->embarque_id);
                $embarque->status_embarque = 'F';
                $embarque->save();
                // criar mensagem de retorno para o usuário que os embarques foram finalizados concatenando com a mensagem dos containers
                $msg = 'Container finalizado com sucesso e Embarque finalizado com sucesso! ';
            }
            else
                $msg = 'Não foi possível finalizar o Embarque pois existem containers pendentes!';

            return ['return' => true, 'mensagem' => $msg];

        } catch (\Throwable $th) {
            return ['return' => false, 'mensagem' => 'Erro ao finalizar container! '. $th->getMessage()];
        }            
    }

    public static function QuantidadeDeInconsistenciasContainers($embarque_id){

        //verifica se todos os containers do embarque estão com finalizado igual a 1
        $containers = EmbarqueContainer::where('embarque_id', $embarque_id)->where('finalizado', 0)->get();
        return $containers->count();

    }

    public static function QuantidadeReprovada(){ 

        //verifica se todos os containers do embarque estão com finalizado igual a 1
        $containers = EmbarqueContainer::where('status', 2)->get();
        return $containers->count();

    }

    public static function ContainerLiberadoGerouQuestionario($container){
        // verifica se o Embarque está liberao
        $embarque = Embarque::find($container->embarque_id);
        if($embarque->status_embarque == 'L'){
            // verifica se o container gerou questionário
            $questionario = EmbarqueContainerChecklistResposta::where('embarques_containers_id', $container->id)->get();
            if($questionario->count() == 0)
                return true;
            else
                return false;
        }    
        else
            return false;

    }

    public static function GerarCheklistParaUmContainer($container){

        $embarque = Embarque::find($container->embarque_id);

        // so poderá gerar para embarques bloqueados
        if($embarque->status_embarque == 'B'){
            return array('status' => false, 'mensagem' => 'Não será possível gerar o Questionário para este Container pois o Embarque não está Bloqueado' );
        }

        if($embarque->em_edicao_usu_id > 0 && $embarque->em_edicao_usu_id != auth()->user()->id){
            return array('status' => false, 'mensagem' => 'Questionário está em Edição por outro usuário' );
        }

        $EmbarqueContainer = EmbarqueContainer::where('id', $container->id)->get();

        if($EmbarqueContainer->count() > 0){

            foreach($EmbarqueContainer as $emb_container){
            
                if($emb_container->questionario_id == 0){
                    return array('status' => false, 'mensagem' => 'Faltando Questionário no Container ' . $emb_container->descricao);
                }

                if($emb_container->lacracao_id == 0){
                    return array('status' => false, 'mensagem' => 'Faltando Lacração no Container ' . $emb_container->descricao);
                }                

                $modalidades_embarque = EmbarqueContainerModalidade::where('container_id', $emb_container->id)->get();

                if($modalidades_embarque->count() > 0){

                    foreach($modalidades_embarque as $modalidade){
                    
                        if($modalidade->modalidade_id == 0){
                            return array('status' => false, 'mensagem' => 'Faltando Modalidade no Container ' . $emb_container->descricao);
                        }
                    }
                }
                else{
                    return array('status' => false, 'mensagem' => 'Faltando Modalidade no Container ' . $emb_container->descricao);
                }
            }
        }    
        
        DB::beginTransaction();
        try {
            
            foreach($EmbarqueContainer as $emb_container){
            //'embarque_id',
            //'questionario_id',
                if($emb_container->questionario_id > 0){
                    
                    $questionario = Questionario::where('id', $emb_container->questionario_id)->first();
                    $questionario_perguntas = QuestionarioPerguntas::where('questionario_id', $questionario->id)->orderBy('sequencia')->get();
                                        
                    if($questionario_perguntas->count() > 0){                       
                        $i = 1;
                        foreach($questionario_perguntas as $quest_pergunta){

                            EmbarqueContainerChecklistResposta::insert([
                                'embarques_containers_id' => $emb_container->id,
                                'pergunta'                => $quest_pergunta->pergunta,
                                'texto'                   => $quest_pergunta->texto,
                                'questionario_id'         => $questionario->id,
                                'questionario_pergunta_id'=> $quest_pergunta->id,
                                'embarque_id'             => $container->embarque_id,
                                'pergunta_imagem'         => $quest_pergunta->pergunta_imagem,
                                'sequencia'               => $quest_pergunta->sequencia,
                                'pergunta_dependente_id'  => $quest_pergunta->pergunta_dependente_id,
                                'visivel'                 => $quest_pergunta->pergunta_dependente_id > 0 ? 'N' : 'S',
                                'pergunta_neutra'         => $quest_pergunta->pergunta_neutra,
                                'pergunta_finaliza_negativa' => $quest_pergunta->pergunta_finaliza_negativa
                            ]);
                            $i++;
                        }
                    }
                
                    $modalidades_embarques = EmbarqueContainerModalidade::where('container_id', $emb_container->id)->orderBy('id')->get();

                    if($modalidades_embarques->count() > 0){
                        $i = 1;
                        foreach($modalidades_embarques as $modalidade_embarque){
                         
                            $modalidades_roteiros = ModalidadeRoteiro::where('modalidade_id', $modalidade_embarque->modalidade_id)->orderBy('sequencia')->get();

                            if($modalidades_roteiros->count() > 0){

                                foreach($modalidades_roteiros as $modalidade_roteiro){

                                    //consulta uma EmbarqueContainerModalidadeResposta pela descricao para ver se já existe
                                    $descricao = EmbarqueContainerModalidadeResposta::where('descricao', $modalidade_roteiro->descricao)
                                                                                ->where('embarque_id', $container->embarque_id)
                                                                                ->where('embarques_containers_id', $emb_container->id)
                                                                                ->first();

                                    if($descricao == null)
                                    {
                                        EmbarqueContainerModalidadeResposta::insert([
                                            'embarques_containers_id' => $emb_container->id,
                                            'descricao'               => $modalidade_roteiro->descricao,
                                            'texto'                   => $modalidade_roteiro->texto,
                                            'modalidade_id'           => $modalidade_embarque->modalidade_id,
                                            'modalidade_roteiro_id'   => $modalidade_roteiro->id,
                                            'embarque_id'             => $container->embarque_id,
                                            'sequencia'               => $modalidade_roteiro->sequencia,
                                            'pergunta_neutra'         => $modalidade_roteiro->pergunta_neutra,
                                            'pergunta_finaliza_negativa' => $modalidade_roteiro->pergunta_finaliza_negativa
                                        ]);
                                        $i++;
                                    }
                                }
                            }
                        }
                    }

                    $lacracao = Lacracao::where('id', $emb_container->lacracao_id)->first();
                    $lacracao_perguntas = LacracaoRoteiro::where('lacracao_id', $lacracao->id)->orderBy('sequencia')->get();
                                        
                    if($lacracao_perguntas->count() > 0){                       
                        $i = 1;
                        foreach($lacracao_perguntas as $lacre_pergunta){

                            EmbarqueContainerLacracaoResposta::insert([
                                'embarques_containers_id'   => $emb_container->id,
                                'descricao'                 => $lacre_pergunta->descricao,
                                'texto'                     => $lacre_pergunta->texto,
                                'lacracao_id'               => $lacracao->id,
                                'lacracao_roteiro_id'       => $lacre_pergunta->id,
                                'embarque_id'               => $container->embarque_id,
                                'sequencia'                 => $lacre_pergunta->sequencia,
                                'pergunta_neutra'           => $lacre_pergunta->pergunta_neutra,
                                'pergunta_finaliza_negativa' => $lacre_pergunta->pergunta_finaliza_negativa
                            ]);
                            $i++;
                        }
                    }
                }
            } 
            $embarque->status_embarque = 'L';
            $embarque->em_edicao = 0;
            $embarque->em_edicao_usu_id = null;

            $embarque->save();
            
            DB::commit();
            return array('status' => true, 'mensagem' => 'Gerado com Sucesso');
        } catch (\Throwable $th) {
            DB::rollBack();

            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 500));
        }
    }
}
