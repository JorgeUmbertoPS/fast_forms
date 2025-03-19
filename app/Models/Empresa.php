<?php

namespace App\Models;

use App\Models\UserAdmin;
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
        "plano_id",
        "cnpj",
        "ie",
        "razao_social",
        "email",
        "cnae",
        "qtd_licencas",
        "telefone",
        "start_date",
        "end_date",
        "logo",
        "im",
        "segmento_id"

    ];

    public function users()
    {
        return $this->hasMany(UserAdmin::class, 'empresa_id')->where('admin_cliente', 1);
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
