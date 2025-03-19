<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\PedidoEmailNotification;
use App\Observers\ModeloFormularioEmpresaObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ModeloFormularioEmpresaObserver::class])]
class ModeloFormularioEmpresa extends Model
{
    use HasFactory;

    protected $table = "modelo_formularios_empresas";

    public $incrementing = true;

    protected $fillable = [
        'empresa_id',
        'modelo_id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];


    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function modelo()
    {
        return $this->belongsTo(ModeloFormulario::class, 'modelo_id');
    }

    public static function LiberarParaEmpresa($record){

        try {

            DB::beginTransaction();

            $modelo = ModeloFormularioEmpresa::find($record->id);
            $modelo->status = 3;
            $modelo->save();

            $linhas = [];
            $linhas[0] = 'Seu formulário foi liberado com sucesso!';
            $linhas[1] = 'O formulário liberado foi o : ' . $record->modelo->nome;
            $linhas[2] = 'Para acessa-lo, clique no Menu Modelos Disponíveis, caso seu perfil permita.';

            $user = User::find($modelo->user_id);
            $user->notify(new PedidoEmailNotification($linhas));

            DB::commit();

            return array('status' => true, 'mensagem' => 'Aguarde que enviaremos um email de confirmação');

        } catch (\Throwable $th) {
            DB::rollBack();
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100));
        }



        return ['status'=>true, 'mensagem'=>'Modelo liberado com sucesso!'];
    }
}
