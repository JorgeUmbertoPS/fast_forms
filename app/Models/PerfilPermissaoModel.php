<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerfilPermissaoModel extends Model
{
    use HasFactory;

    protected $table = 'perfil_permissao';
    protected static function booted(): void
    {
        // SE USUARIO ESTIVER LOGADO
        if(auth()->check()){

            static::addGlobalScope('empresas', function (Builder $query) {
                    $query->where('perfil_permissao.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('perfil_permissao.empresa_id', 0);
            });
        }
    }

    protected $fillable = [
        'perfil_id',
        'permissao_id',
        'empresa_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function perfil()
    {
        return $this->belongsTo(PerfilModel::class, 'perfil_id');
    }
    public function permissao()
    {
        return $this->belongsTo(PermissaoModel::class, 'permissao_id');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
