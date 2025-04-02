<?php

namespace App\Models;

use App\Models\ModeloPergunta;
use Illuminate\Support\Facades\DB;
use App\Models\ModeloPerguntaBloco;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\ModeloFormularioObserver;
use App\Notifications\PedidoEmailNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ModeloFormularioObserver::class])]
class ModeloFormulario extends Model
{
    use HasFactory;

    //constantes para status
    const STATUS_NAO_PEDIDO = 0;
    const STATUS_FOI_PEDIDO = 1;
    const STATUS_FOI_REJEITADO = 2;
    const STATUS_FOI_LIBERADO = 3;
    const STATUS_FOI_UTILIZADO = 4;
    const STATUS_NAO_FOI_LIBERADO = 5;

    protected $table = "modelo_formularios";

    public $incrementing = true;

    protected $fillable = [
        'id',
        'nome',
        'segmento_id',
        'empresa_id',
        'versao',
        'versao_nova',
        'status',
        'created_at',
        'updated_at',

    ];

    protected $hidden = [
      //  'empresa_id'
    ];


    private static $status_pedido = [
        0 => 'Não foi pedido',
        1 => 'Foi pedido',
        2 => 'Foi cancelado',
        3 => 'Foi liberado',
        4 => 'Foi utilizado',
        5 => 'Não foi liberado',
    ];

    public function segmento() {
        return $this->belongsTo(Segmento::class);
    }

    public function EtapasModelos():HasMany{
        return $this->hasMany(ModeloPerguntaBloco::class, 'modelo_id');
    }

    public function respostasPerguntas(){
        return $this->hasMany(ModeloResposta::class,'bloco_id');
    }

    //UtilizarModelo = criar um registro na tabela modelo_formularios_empresas com status 0
    public static function PedirModelo($record){

        try {

            DB::beginTransaction();
            $modelo = new ModeloFormularioEmpresa();
            $modelo->empresa_id = Auth::user()->empresa_id;
            $modelo->modelo_id = $record->id;
            $modelo->user_id = Auth::user()->id;            
            $modelo->status = 1;
            $modelo->save();

            $linhas = [];
            $linhas[0] = 'A empresa ' . Auth::user()->empresa->nome . ', através do usuário '. Auth::user()->name . ' solicitou um novo formulário.';
            $linhas[1] = 'O formulário solicitado é: ' . $record->nome;
            $linhas[2] = 'Para liberar o formulário, acesse o cadastro da Empresa e clique em "Modelos de Formulários"';

            $user = User::find(1);
            $user->notify(new PedidoEmailNotification($linhas));

            DB::commit();

            return array('status' => true, 'mensagem' => 'Aguarde que enviaremos um email de confirmação');

        } catch (\Throwable $th) {
            DB::rollBack();
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100));
        }
    }

    
    public function LiberarBloquear($id){
        try {
            $modelo = self::find($id);
            if($modelo->status == 0){
                if(self::where('id', $id)->update(['status'=> 1])) {
                    return ['return' => true, 'mensagem' => 'Liberado com sucesso'];
                } else {
                    return ['return'=> false,'mensagem'=> 'Não foi possível liberar'];    
                }
            } else {
                if(self::where('id', $id)->update(['status'=> 0])) {
                    return ['return' => true, 'mensagem' => 'Bloqueado com sucesso'];
                } else {
                    return ['return'=> false,'mensagem'=> 'Não foi possível liberar'];    
                }
            }
        } catch (\Throwable $th) {
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100)); 
        }

    }

    public static function GerarFormulario($modelo){

        try {

            $data['data_termino'] = Date('Y-m-d H:i:s');
            $data['data_inicio']  = Date('Y-m-d H:i:s');
            
            $empresa_id = Auth::user()->empresa_id;

            $formulario = new Formulario();
            $formulario->modelo_id = $modelo['id'];
            $formulario->empresa_id = $empresa_id;
            $formulario->nome = $modelo['nome'];
            $formulario->envia_email_etapas = 0;
            $formulario->obriga_assinatura = 0;
            $formulario->avisar_dias_antes = 0;
            $formulario->data_inicio = null;
            $formulario->data_termino = null;
            $formulario->created_at = Date('Y-m-d H:i:s');
            $formulario->updated_at = Date('Y-m-d H:i:s');
            $formulario->save();

            // gerando bloco de perguntas para os Clientes
            $blocos = DB::table('modelo_perguntas_blocos')->where('modelo_id', $modelo['id'])->get();

            foreach($blocos as $row){

                $data_bloco                 = new FormularioPerguntaBloco();  //formularios_perguntas_blocos
                $data_bloco->titulo         = $row->titulo;
                $data_bloco->sub_titulo     = $row->sub_titulo;
                $data_bloco->descricao      = $row->descricao;
                $data_bloco->form_id        = $formulario->id;
                $data_bloco->empresa_id     = $empresa_id;
                $data_bloco->ordem          = $row->ordem;
                $data_bloco->created_at     = Date('Y-m-d H:i:s');
                $data_bloco->updated_at     = Date('Y-m-d H:i:s');
                $data_bloco->ordem          = $row->ordem;
                $data_bloco->instrucoes     = $row->instrucoes;
                $data_bloco->observacoes    = $row->observacoes;
                $data_bloco->icon           = $row->icon;
              //  $data_bloco->modelo_id      = $modelo['id'];
                $data_bloco->save();

                $perguntas = DB::table('modelo_perguntas')->where('bloco_id', $row->id)->get();

                foreach($perguntas as $pergunta){

                    $data_pergunta                      = new FormularioPergunta(); //formularios_perguntas
                    $data_pergunta->nome                = $pergunta->nome;
                    $data_pergunta->resposta_tipo_id    = $pergunta->resposta_tipo_id;
                    $data_pergunta->empresa_id          = $empresa_id;
                    $data_pergunta->bloco_id            = $data_bloco->id;
                    $data_pergunta->form_id             = $formulario->id;
                    $data_pergunta->user_id             = Auth::user()->id;
                    $data_pergunta->created_at          = Date('Y-m-d H:i:s');
                    $data_pergunta->updated_at          = Date('Y-m-d H:i:s');
                    $data_pergunta->ordem               = $pergunta->ordem;
                    $data_pergunta->id_mascara           = $pergunta->id_mascara;                    
                    $data_pergunta->obriga_justificativa = $pergunta->obriga_justificativa;
                    $data_pergunta->obriga_midia         = $pergunta->obriga_midia;
                    $data_pergunta->save();

                    $respostas = DB::table('modelo_respostas')->where('pergunta_id', $pergunta->id)->get();
                    foreach($respostas as $resposta){
                        $data_resposta                       = new FormularioResposta();
                        $data_resposta->pergunta_id          = $data_pergunta->id;
                        $data_resposta->empresa_id           = $empresa_id;

                        if($resposta->nome == null){
                            $data_resposta->nome = $pergunta->nome;
                        } else {
                            $data_resposta->nome = $resposta->nome;
                        }

                        $data_resposta->created_at           = Date('Y-m-d H:i:s');
                        $data_resposta->updated_at           = Date('Y-m-d H:i:s');
                        $data_resposta->ordem                = $resposta->ordem;
                        $data_resposta->icon                 = $resposta->icon;
                                                
                        $data_resposta->save();
                    }
                }   

            }    

            $modelo_empresa =  ModeloFormularioEmpresa::where('modelo_id', $modelo['id'])
                                                        ->where('empresa_id', $empresa_id)->first();

            $modelo_empresa->status = 4;
            $modelo_empresa->save();

            return array('status' => true, 'mensagem' => '');

        } catch (\Throwable $th) {
            dd($th);
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100));
        }
    }

    public static function StatusPedidoUtilizacao($record){

        //0 - Nao foi pedido, 
        //1 - Foi Pedido, 
        //2 - Foi Cancelado, 
        //3 - Foi Liberado, //
        //4 - Foi Utilizado

        $modelo = ModeloFormularioEmpresa::where('modelo_id', $record->id)
                                        ->where('empresa_id', Auth::user()->empresa_id)->first();

        if($modelo){
            return ['status' => $modelo->status, 'label' => self::$status_pedido[$modelo->status]];
        }
    }



    public static function ObterDadosView($id)
    {
        // Carregar blocos com suas perguntas e os tipos de resposta e máscara já carregados para evitar múltiplas consultas
        $blocos = DB::table('modelo_perguntas_blocos')
                    ->where('modelo_id', $id)
                    ->orderBy('ordem')
                    ->get();
    
        // Inicializar array de dados
        $dados = [];
    
        if ($blocos->isNotEmpty()) {
            foreach ($blocos as $bloco) {
                // Carregar as perguntas do bloco e os tipos de resposta e máscaras relacionados
                $perguntas = ModeloPergunta::where('bloco_id', $bloco->id)
                            ->orderBy('ordem')
                            ->get();
    
                // Se houver perguntas, processar
                if ($perguntas->isNotEmpty()) {
                    $resp_blocos = $perguntas->map(function ($pergunta) {
                        return [
                            'nome'                  => $pergunta->nome,
                            'resposta_tipo'         => ModeloRespostaTipo::find($pergunta->resposta_tipo_id)['nome'] ?? '',
                            'mascara'               => ModeloMascara::find($pergunta->mascara_id)['nome'] ?? '',
                            'obriga_justificativa'  => $pergunta->obriga_justificativa == 1 ? 'Sim' : 'Não',
                            'obriga_midia'          => $pergunta->obriga_midia == 1 ? 'Sim' : 'Não',
                        ];
                    });
    
                    // Adicionar o bloco ao array de dados
                    $dados['bloco'][] = [
                        'nome' => $bloco->descricao,
                        'perguntas' => $resp_blocos->toArray()
                    ];
                }
            }
        }
    
        return $dados;
    }
    


}
