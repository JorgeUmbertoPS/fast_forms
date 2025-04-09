<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        "nome",
        "cnpj",
        "ie",
        "razao_social",
        "email",
        "cnae",
        "telefone",
        "logo",
        "im",
        'imagem_base64'
    ];

    protected static function booted(): void
    {
        if(auth()->check() && auth()->user()->empresa_id != null){
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('empresas.id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('empresas.id', null);
            });
        }

    } 
}
