<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EmbarqueContainerModalidadeResposta extends Model 
{
    use HasFactory;

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_containers_checklist_modalidades';
    protected $logName      = 'Modalidades do Checklist';

    protected $fillable = [
        'id',
        'embarques_containers_id',
        'embarque_id',
        'descricao',
        'texto',
        'imagem',
        'modalidade_id',
        'status',
        'sequencia',
        'user_id',
        'modalidade_roteiro_id',
        'pergunta_dependente_id',
        'pergunta_neutra',
        'pergunta_finaliza_negativa'
    ];

    protected $casts = [
        'pergunta_neutra' => 'boolean',
        'pergunta_finaliza_negativa' => 'boolean'
    ];

    public static function FinalizaFaseModalidade($embarque_id, $modalidade_id){
        self::where('id', $embarque_id)
            ->where('id', $modalidade_id)
            ->update(['status' => 'F']);
    }

    public static function total_itens($id){
        $ret['total_itens'] = self::where('embarques_containers_id', $id)->count();
        $ret['total_itens_abertos'] = self::where('embarques_containers_id', $id)->whereNull('imagem')->count();
        $ret['total_itens_incosistencias'] = self::where('embarques_containers_id', $id)->where('imagem','Não')->count();
        return $ret;
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

    public static function QtdInconsistenciaModalidades($embarque_id, $embarques_containers_id){
       // dd($embarque_id, $embarques_containers_id);

        $qtd = 0;
        $dados = self::where('embarques_containers_id', $embarques_containers_id)
                    ->where('embarque_id', $embarque_id)
                    ->get();

        if($dados->count() > 0){
            
            foreach($dados as $dado){
                if($dado->pergunta_neutra == 0 && $dado->imagem == ''){
                    $qtd++;
                }
            }
        }
        
        return $qtd;                                 
    }

    public static function QtdInconsistenciaTotal($embarque_id, $embarques_containers_id){
        return self::QtdInconsistenciaModalidades($embarque_id, $embarques_containers_id);
    }

    public static function TentarFinalizarEtapa($itemData){

        $msg = '';

        $inconsistencias_chk = self::QtdInconsistenciaModalidades($itemData['embarque_id'], $itemData['embarques_containers_id']);

        if($inconsistencias_chk > 0){
            $msg .= "<li>Incosistências com Modalidades</li>";

            return ['return' => false, 'mensagem' => $msg];
        }
        else{

            self::where('embarques_containers_id', $itemData['embarques_containers_id'])
                ->where('embarque_id', $itemData['embarque_id'])
                ->update([
                    'status' => 'F'
                ]);

            // marcar o usuario que finalizou a etapa na tabela de embarques_containers usando o Model 
            EmbarqueContainer::where('id', $itemData['embarques_containers_id'])->update([
                'user_id_modalidade' => auth()->user()->id,
                'data_fechamento_modalidade' => date('Y-m-d H:i:s')
            ]);
 
            return ['return' => true, 'mensagem' => 'Etapa de Modalidade finalizada com sucesso!'];
                        
        }
 
    }

    public static function StatusDisabledEtapaChecklist($embarque_id, $embarques_containers_id){
        ///usado para verificar se na etapa anterior tem inconsistencias
        $total = EmbarqueContainerChecklistResposta::QtdInconsistenciaTotal($embarque_id, $embarques_containers_id);

        $total_perguntas = EmbarqueContainerChecklistResposta::where('embarques_containers_id', $embarques_containers_id)
                                ->where('embarque_id', $embarque_id)
                                ->count();
        $total_respostas = EmbarqueContainerChecklistResposta::where('embarques_containers_id', $embarques_containers_id)
                                ->where('embarque_id', $embarque_id)
                                ->where('status', 'F')
                                ->count();
        
        $status_fechado = $total_perguntas == $total_respostas;
//dd($total_perguntas , $total_respostas, $status_fechado, $total);
        if($total > 0 || !$status_fechado){
            return true;
        }
        else{
            return false;
        }

    }

    public static function ObterHelp($record){

        $db = ModalidadeRoteiro::where('modalidade_id', $record->modalidade_id)->where('id', $record->modalidade_roteiro_id)->first();
        if($db){
            return $db->texto_help;
        }

        return null;
    }

    public static function ValidarSalvaImagem($record){


        $item = self::where('embarques_containers_id', $record->id)->where('embarque_id', $record->embarque_id)->first();

        //verificar se o usuario que esta respondendo é o mesmo que abriu a etapa
        if($item != null){
            if($item->user_id!= null){
            // dd($item->user_id, auth()->user()->id);
                if($item->user_id != auth()->user()->id){
                    $user = User::where('id', $item->user_id)->first();
                    return [
                        'return' => false, 
                        'mensagem' => 'Você não poderá responder. Responsável: '.$user['name'] 
                    ];
                }
            }
        }

        return ['return' => true, 'mensagem' => ''];

    }

    public static function SalvaImagem($state, $record){

        $extension = pathinfo($state->getFilename(), PATHINFO_EXTENSION);

        $file_name = 'emb_'.$record->embarque_id.'_cont_'.$record->embarques_containers_id.'_mod_' . $record->modalidade_roteiro_id. '_'. date('dmYhis') .'.'.$extension;

        self::where('id', $record->id)->update([
            'imagem' => $file_name
        ]);

       // Storage::move('livewire-tmp/' . $state->getFilename(), 'public/' . $file_name);
       
       $file = storage_path('app/livewire-tmp//' . $state->getFilename());

       $manager = new ImageManager(Driver::class);
       $image = $manager->read($file); // 800 x 600
       $image->resizeDown(600, 800); //  400 x 300

       $image->save(storage_path('app/public//' . $file_name));

       self::where('embarques_containers_id', $record->embarques_containers_id)
       ->where('embarque_id', $record->embarque_id)
       ->whereNull('user_id')
       ->update([
           'user_id' => auth()->user()->id
       ]);
       
    }

    public static function ChecklistModalidadeFinalizado($embarque_id, $embarques_containers_id){

        return (self::where('embarque_id', $embarque_id)
                    ->where('embarques_containers_id', $embarques_containers_id)
                    ->where('status', 'A')
                    ->count() == 0);
                    
    }

    public static function excluitChecklistModalidadeResposta($id){
        self::where('id', $id)
            ->delete();
    }

}
