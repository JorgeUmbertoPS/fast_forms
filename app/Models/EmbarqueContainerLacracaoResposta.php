<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EmbarqueContainerLacracaoResposta extends Model
{
    use HasFactory;

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_containers_lacracoes_respostas';
    protected $logName      = 'Lacaração de Embarque';

    protected $fillable = [
        'id',
        'embarques_containers_id',
        'descricao',
        'resposta',
        'imagem',
        'texto',
        'lacracao_id',
        'status',
        'user_id',
        'embarque_id',
        'lacracao_roteiro_id',
        'sequencia',
        'pergunta_dependente_id',
        'pergunta_neutra',
        'pergunta_finaliza_negativa'
    ];

    protected $casts = [
        'pergunta_neutra' => 'boolean',
        'pergunta_finaliza_negativa' => 'boolean'
    ];    

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

    public static function QtdInconsistenciaLacracao($embarque_id, $embarques_containers_id){
       // dd($embarque_id, $embarques_containers_id);
        return self::where('embarques_containers_id', $embarques_containers_id)
                                ->where('embarque_id', $embarque_id)
                                ->whereNull('imagem')
                                ->where('pergunta_neutra', 0)
                                ->count();

        $qtd = 0;
        $dados = self::where('embarques_containers_id', $embarques_containers_id)
                    ->where('embarque_id', $embarque_id)
                    ->whereNull('imagem')
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
        return self::QtdInconsistenciaLacracao($embarque_id, $embarques_containers_id);
    }

    public static function TentarFinalizarEtapa($itemData){

        $msg = '';

        $inconsistencias_chk = self::QtdInconsistenciaLacracao($itemData['embarque_id'], $itemData['embarques_containers_id']);

        if($inconsistencias_chk > 0){
            $inconsistencias_chk > 0 ? $msg = "<li>Incosistências com Lacres</li>" : $msg = "";

            return ['return' => false, 'mensagem' => $msg];
        }
        else{
            try {
                DB::beginTransaction();
                
                //embarques_containers_lacracoes_respostas
                self::where('embarques_containers_id', $itemData['embarques_containers_id'])
                            ->where('embarque_id', $itemData['embarque_id'])
                            ->update([
                                'status' => 'F'
                            ]);

                // marcar o usuario que finalizou a etapa na tabela de embarques_containers usando o Model
                EmbarqueContainer::where('id', $itemData['embarques_containers_id'])->update([
                    'user_id_lacres' => auth()->user()->id,
                    'data_fechamento_lacres' => date('Y-m-d H:i:s')
                ]);

                $ret = EmbarqueContainer::FinalizaContainer($itemData['embarques_containers_id']);
                // se retornar false, não finaliza o container
                if(!$ret['return']){
                    DB::rollBack();
                    return ['return' => false, 'mensagem' => $ret['mensagem']];
                } // senão, finaliza o container e retornar mensagem de sucesso
                else{
                    DB::commit();
                    return ['return' => true, 'mensagem' => 'Etapa de Lacres finalizada com sucesso e '.$ret['mensagem'] ];
                }


            } catch (\Throwable $th) {
                Db::rollBack();
                return ['return' => false, 'mensagem' => 'Erro ao finalizar etapa de Lacres! '. $th->getMessage()];
            }
                        
        }
 
    }

    public static function QtdInconsistenciaTotalEtapaModalidade($embarque_id, $embarques_containers_id){
        ///usado para verificar se na etapa anterior tem inconsistencias
        return EmbarqueContainerModalidadeResposta::QtdInconsistenciaTotal($embarque_id, $embarques_containers_id);
    }

    public static function ObterHelp($record){

        $db = LacracaoRoteiro::where('lacracao_id', $record->lacracao_id)->where('id', $record->lacracao_id)->first();
        if($db){
            return $db->texto_help;
        }

        return null;
    }

    public static function SalvaImagem($state, $record){

        $extension = pathinfo($state->getFilename(), PATHINFO_EXTENSION);

        $file_name = 'emb_'.$record->embarque_id.'_cont_'.$record->embarques_containers_id.'_lac_' . $record->lacracao_id. '_'. date('dmYhis') .'.'.$extension;

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

    public static function ChecklistLacreFinalizado($embarque_id, $embarques_containers_id){

        return (self::where('embarque_id', $embarque_id)
                    ->where('embarques_containers_id', $embarques_containers_id)
                    ->where('status', 'A')
                    ->count() == 0);
                    
    }

    public static function ValidarSalvaImagem($record){

       // dd($record);
        $item = self::where('embarques_containers_id', $record->id)->where('embarque_id', $record->embarque_id)->first();
      //  dd($item);
        //verificar se o usuario que esta respondendo é o mesmo que abriu a etapa
        if($item != null){

            if($item->user_id!= null){
                //dd($item->user_id, auth()->user()->id);
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

    public static function excluitChecklistLacracaoResposta($id){
        self::where('id', $id)
            ->delete();
    }
}
