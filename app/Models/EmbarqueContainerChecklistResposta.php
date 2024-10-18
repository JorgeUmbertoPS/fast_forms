<?php

namespace App\Models;

use Imagick;
use App\Models\Utils;
use App\Traits\TraitLogs;
use Spatie\MediaLibrary\HasMedia;
use Intervention\Image\ImageManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmbarqueContainerChecklistResposta extends Model
{
    use HasFactory; 

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_containers_checklist_respostas';
    protected $logName      = 'Resposta de Checklist';

    protected $fillable = [
        'id',
        'embarques_containers_id',
        'pergunta',
        'resposta',
        'texto',
        'questionario_id',
        'embarque_id',
        'sequencia',
        'visivel',
        'status',
        'pergunta_imagem',
        'user_id',
        'questionario_pergunta_id',
        'pergunta_dependente_id',
        'pergunta_neutra',
        'pergunta_finaliza_negativa'
        ];

       // casts
    protected $casts = [
        'pergunta_neutra' => 'boolean',
        'pergunta_finaliza_negativa' => 'boolean'
    ];
        
    public static function Responder($record, $resposta){
        $itemData = $record->id;

        $item = self::where('id', $itemData)->first();

        if($item->status == 'F'){
            return ['return' => false, 'mensagem' => 'Etapa de Checklist já finalizada!'];
        }
        else{

            self::where('id', $itemData)->update([
                'resposta' => $resposta
            ]);

            // pegar o id que foi respondido
            $itemData = self::where('id', $itemData)->first();
            
            $perguntas_dependentes = self::where('pergunta_dependente_id', $record->questionario_pergunta_id)
                                         ->where('embarques_containers_id', $record->embarques_containers_id)
                                         ->where('embarque_id', $record->embarque_id)
            ->get();

            if($perguntas_dependentes->count() > 0){

                foreach($perguntas_dependentes as $pergunta_dependente){
                    self::where('id', $pergunta_dependente->id)->update([
                        'visivel' => substr($resposta,0,1)
                    ]);
                }
            }

            // atualizar todos os user_id que estiverem null com o id do usuario logado
            self::where('embarques_containers_id', $record->embarques_containers_id)
                ->where('embarque_id', $record->embarque_id)
                ->whereNull('user_id')
                ->update([
                    'user_id' => auth()->user()->id
                ]);

            return ['return' => true, 'mensagem' => ''];
        }

    }

    public static function ValidarResponder($record){
       // dd($record);
        $item = self::where('embarques_containers_id', $record->id)->where('embarque_id', $record->embarque_id)->first();
   
    
        //verificar se o usuario que esta respondendo é o mesmo que abriu a etapa
        if($item != null){
            if($item->user_id != null){
                if($item->user_id != auth()->user()->id){
                    $user = User::where('id', $item->user_id)->first();
                    return [
                        'return' => false, 
                        'mensagem' => 'Você não poderá responder. Responsável: '.$user['name'] 
                    ];
                }
            }
        }
        return [
            'return' => true, 
            'mensagem' => '' 
        ];
    }

    public static function QtdInconsistenciaQuestionario($embarque_id, $embarques_containers_id){

       // dd($embarque_id, $embarques_containers_id);
        $qtd = 0;
        $dados = self::where('embarques_containers_id', $embarques_containers_id)
                                ->where('embarque_id', $embarque_id)
                                ->where('pergunta_imagem', 'P')
                                ->where('visivel', 'S')
                                ->get();

        if($dados->count() > 0){
            
            foreach($dados as $dado){
                // pergunta nao é neutra e nao foi respondida
                if($dado->pergunta_neutra == 0 && $dado->resposta == ''){
                    $qtd++;
                }
                
                // a pergunta não pode finalizar negativa e a resposta é negativa
                if($dado->pergunta_finaliza_negativa == 0 && $dado->resposta == 'Não'){
                    $qtd++;
                }
            }
        }        

        return $qtd;
    }

    public static function QtdInconsistenciaQuestionarioImagem($embarque_id, $embarques_containers_id){

        return self::where('embarques_containers_id', $embarques_containers_id)
                    ->where('embarque_id', $embarque_id)
                    ->whereNull('resposta')
                    ->where('pergunta_imagem', 'I')
                    ->where('visivel', 'S')
                    ->where('pergunta_neutra', 0)
                    ->count();
    }

    public static function QtdInconsistenciaTotal($embarque_id, $embarques_containers_id){
        return self::QtdInconsistenciaQuestionario($embarque_id, $embarques_containers_id) +
                self::QtdInconsistenciaQuestionarioImagem($embarque_id, $embarques_containers_id);
    }

    public static function ValidarFinalizacaoDaEtapa($itemData){

        $msg = '';

        $inconsistencias_chk = self::QtdInconsistenciaQuestionario($itemData['embarque_id'], $itemData['embarques_containers_id']);
        $inconsistencias_img = self::QtdInconsistenciaQuestionarioImagem($itemData['embarque_id'], $itemData['embarques_containers_id']);

        if($inconsistencias_chk > 0 || $inconsistencias_img > 0){
            if($inconsistencias_chk > 0)
                $msg = "<li>Incosistências com Questionários</li>";
            if($inconsistencias_img > 0)
                $msg .= "<li>Incosistências com Imagens do Questionário</li>";

            return ['return' => false, 'mensagem' => $msg];
        }
        else{
            return ['return' => true, 'mensagem' => 'Etapa de Checklist poderá ser finalizada!'];
        }
 
    }

    public static function TentarFinalizarEtapa($itemData){

        $msg = '';

        $inconsistencias_chk = self::QtdInconsistenciaQuestionario($itemData['embarque_id'], $itemData['embarques_containers_id']);
        $inconsistencias_img = self::QtdInconsistenciaQuestionarioImagem($itemData['embarque_id'], $itemData['embarques_containers_id']);

        if($inconsistencias_chk > 0 || $inconsistencias_img > 0){
            if($inconsistencias_chk > 0)
                $msg = "<li>Incosistências com Questionários</li>";
            if($inconsistencias_img > 0)
                $msg .= "<li>Incosistências com Imagens do Questionário</li>";

            return ['return' => false, 'mensagem' => $msg];
        }
        else{
            self::where('embarques_containers_id', $itemData['embarques_containers_id'])
                ->where('embarque_id', $itemData['embarque_id'])
                ->update([
                    'status' => 'F'
                ]);
            
            // marcar o usuario que finalizou a etapa na tabela de embarques_containers usando o Model 
            EmbarqueContainer::where('id', $itemData['embarques_containers_id'])
            ->update([
                'user_id_questionario' => auth()->user()->id,
                'data_fechamento_questionario' => date('Y-m-d H:i:s')
            ]);

            return ['return' => true, 'mensagem' => 'Etapa de Checklist finalizada com sucesso!'];
        }
 
    }

    public static function StatusDeCancelamento($itemData){
        // retorn false liga o botãode tentar finalizar etapa e desliga o re reprovar container
        // return true liga o botão de reprovar container e desliga o botão de tentar finalizar etapa

        //caso alguma pergunta do questionário seja respondido como NEGATIVA que não esteja marcada como "Pergunta Finaliza Negativa"
        //"Você deseja reprovar este container e não dar sequência ao processo de embarque?"
        /*
            "resposta" => "Sim"
            "status" => "A"
            "pergunta_imagem" => "P"
            "sequencia" => 1
            "user_id" => 1
            "embarques_containers_id" => 7
            "questionario_id" => 1
            "questionario_pergunta_id" => 1
            "embarque_id" => 5
            "pergunta_dependente_id" => null
            "visivel" => "S"
            "pergunta_neutra" => 0
            "pergunta_finaliza_negativa" => 0
        */

        //selecionar todas perguntas do embarques_containers_id e embarque_id, fazer um foreach e verificar se a resposta é 'Não' e se a pergunta_finaliza_negativa é 0
        // se sim, retornar true, se não, retornar false

        $perguntas = self::where('embarques_containers_id', $itemData['embarques_containers_id'])
                            ->where('embarque_id', $itemData['embarque_id'])
                            ->get();

        foreach($perguntas as $pergunta){
            if($pergunta->resposta == 'Não' && $pergunta->pergunta_finaliza_negativa == 0){
                return true;
            }
        }      

    }

    // reprovar Container
    public static function ReprovarContainer($itemData){

        try {

            // marcar o usuario que finalizou a etapa na tabela de embarques_containers usando o Model 
            EmbarqueContainer::where('id', $itemData['embarques_containers_id'])
            ->update([
                'user_id_questionario' => auth()->user()->id,
                'data_fechamento_questionario' => date('Y-m-d H:i:s'),
                'status' => EmbarqueContainer::STATUS_REPROVADO
            ]);

            // Usar o metodo de Finalizar Container
            $ret = EmbarqueContainer::FinalizaContainer($itemData['embarques_containers_id']);

            if($ret['return'] == false)
                return ['return' => true, 'mensagem' => 'Container reprovado com sucesso!'];
            else
                return ['return' => false, 'mensagem' => 'Container reprovado com sucesso e '. $ret['mensagem']];

        } catch (\Throwable $th) {
            return ['return' => false, 'mensagem' => 'Erro ao reprovar container! '. $th->getMessage()];
        }
    }
 
    public static function UltimaPergunta($itemData):bool{
        /*"id" => 365
        "embarques_containers_id" => 7
        "embarque_id" => 6
        "sequencia" => 1*/

        $total_perguntas = self::where('embarques_containers_id', $itemData['embarques_containers_id'])
                                ->where('embarque_id', $itemData['embarque_id'])
                                ->count();
        return $total_perguntas == (int)$itemData['sequencia'];

    }

    public static function total_itens($id){
        $ret['total_itens'] = self::where('embarques_containers_id', $id)->count();
        $ret['total_itens_abertos'] = self::where('embarques_containers_id', $id)->whereNull('resposta')->count();
        $ret['total_itens_incosistencias'] = self::where('embarques_containers_id', $id)->where('resposta','Não')->count();
        return $ret;
    }

    public static function SalvaImagem($state, $record){

        $extension = pathinfo($state->getFilename(), PATHINFO_EXTENSION);

        $file_name = 'emb_'.$record->embarque_id.'_cont_'.$record->embarques_containers_id.'_chk_' . $record->id. '_'. date('dmYhis') .'.'.$extension;

        self::where('id', $record->id)->update([
            'resposta' => $file_name
        ]);

        $file = storage_path('app/livewire-tmp//' . $state->getFilename());

        $manager = new ImageManager(Driver::class);
        $image = $manager->read($file); // 800 x 600
        $image->resizeDown(600, 800); //  400 x 300

        $image->save(storage_path('app/public//' . $file_name));


    }

    public static function ObterHelp($record){

        $db = QuestionarioPerguntas::where('questionario_id', $record->questionario_id)->where('id', $record->questionario_pergunta_id)->first();
        if($db){
            return $db->texto_help;
        }

        return null;
    }

    public static function ChecklistFinalizado($embarque_id, $embarques_containers_id){

        return (self::where('embarque_id', $embarque_id)
                    ->where('embarques_containers_id', $embarques_containers_id)
                    ->where('status', 'A')
                    ->count() == 0);
                    
    }

    public static function excluitChecklistResposta($id){

        self::where('id', $id)->delete();
    }

}
