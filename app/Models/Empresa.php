<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        "nome",
        "status",
        "cnpj",
        "razao_social",
        "email",
        "telefone",
        "logo",
        "segmento_id",
        "endereco",

    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function segmento()
    {
        return $this->belongsTo(Segmento::class, 'segmento_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'empresa_id')->where('admin_cliente', 1);
    }

    public function plano()
    {
        return $this->belongsTo(EmpresaPlano::class, 'plano_id');
    }

    public function users_empresas(){
        return $this->hasMany(User::class, 'id', 'empresa_id');
    }

    public function empresas_modelos(){
        return $this->hasMany(ModeloFormularioEmpresa::class, 'empresa_id');
    }

    public static function PossuiusuarioAdmin($id){
        //verifica se usuario admin existe
        $usuario = User::where('empresa_id', $id)->where('admin_cliente', 1)->first();
        if($usuario == null){
            return false;
        }
        else{
            return true;
        }
    }

    /*
    protected static function booted(): void
    {
        if(auth()->check() && auth()->user()->empresa_id != null){
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('empresas.id', auth()->user()->id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('empresas.id', null);
            });
        }
    }*/
}
