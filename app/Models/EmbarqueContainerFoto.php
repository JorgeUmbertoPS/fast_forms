<?php

namespace App\Models;

use Sushi\Sushi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmbarqueContainerFoto extends Model
{
    use HasFactory;

    use Sushi;

    protected static $cliente;

    public static function setVar($filters)
    {
        self::$cliente = $filters['cliente'];
        
        return self::query();
    }

    public function getRows()
    {
        $dados = [];
        
      //  if(self::$cliente != ''){
            $embarques = Embarque::join('embarques_containers', 'embarques.id', '=', 'embarques_containers.embarque_id')
                                ->join('clientes', 'clientes.id', '=', 'embarques.cliente_id')
                                ->join('transportadoras', 'transportadoras.id', '=', 'embarques.transportadora_id')
                                ->select('clientes.nome as cliente', 'transportadoras.nome as transportadora', 'embarques.id as embarque_id',
                                        'embarques_containers.container', 'embarques_containers.id as container_id', 'embarques.data', 'embarques.status_embarque')
                                ->get();
                                
                               // dd(self::query());              
            if($embarques){
                $i = 1;
                foreach ($embarques as $embarque){

                    $fotos_questionario = EmbarqueContainerChecklistResposta::where('embarque_id', $embarque->embarque_id)
                                                                            ->where('embarques_containers_id', $embarque->container_id)
                                                                            ->where('pergunta_imagem', 'I')
                                                                            ->get();
                    if($fotos_questionario){
                        foreach($fotos_questionario as $foto_questionario){
                            $dados[]  = array('id' => $i++, 
                                            'cliente' => $embarque->cliente,
                                            'transportadora' => $embarque->transportadora,
                                            'imagem' => url('storage/' . $foto_questionario->resposta),
                                            'container' => $embarque->container,
                                            'data' => $embarque->data,
                                            'embarque_id' => $embarque->embarque_id,    
                                            'tipo_imagem' => 'Questionário',
                                            'status_embarque' => $embarque->status_embarque,
                                            'usuario' => $foto_questionario->user_id > 0 ? User::find($foto_questionario->user_id)->name : 'Usuário não identificado');
                        }
                    }

                    $fotos_lacre = EmbarqueContainerLacracaoResposta::where('embarque_id', $embarque->embarque_id)
                                                                    ->where('embarques_containers_id', $embarque->container_id)
                                                                    ->get();
                    if($fotos_lacre){
                        foreach($fotos_lacre as $foto_lacre){
                            $dados[]  = array('id' => $i++, 
                                            'cliente' => $embarque->cliente,
                                            'transportadora' => $embarque->transportadora,
                                            'imagem' => url('storage/' . $foto_lacre->imagem),
                                            'container' => $embarque->container,
                                            'data' => $embarque->data,
                                            'embarque_id' => $embarque->embarque_id,
                                            'tipo_imagem' => 'Lacre',
                                            'status_embarque' => $embarque->status_embarque,
                                            'usuario' => $foto_questionario->user_id > 0 ? User::find($foto_questionario->user_id)->name : 'Usuário não identificado');
                        }
                    }

                    $fotos_modalidades = EmbarqueContainerModalidadeResposta::where('embarque_id', $embarque->embarque_id)
                                                                            ->where('embarques_containers_id', $embarque->container_id)
                                                                            ->get();
                    if($fotos_modalidades){
                        foreach($fotos_modalidades as $foto_modalidade){
                            $dados[]  = array('id' => $i++, 
                                            'cliente' => $embarque->cliente,
                                            'transportadora' => $embarque->transportadora,
                                            'imagem' => url('storage/' . $foto_modalidade->imagem),
                                            'container' => $embarque->container,
                                            'data' => $embarque->data,
                                            'embarque_id' => $embarque->embarque_id,
                                            'tipo_imagem' => 'Modalidade',
                                            'status_embarque' => $embarque->status_embarque,
                                            'usuario' => $foto_questionario->user_id > 0 ? User::find($foto_questionario->user_id)->name : 'Usuário não identificado');
                        }
                    }


                }
            }
       // }
       // dd($dados);
        return $dados;
    }

    
    
}
