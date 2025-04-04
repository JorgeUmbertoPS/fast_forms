<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissaoModel extends Model
{
    use HasFactory;

    protected $table = 'permissoes';

    protected $fillable = [
        'nome',
        'descricao',
        'slug',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function perfilPermissao()
    {
        return $this->hasMany(PerfilPermissaoModel::class, 'permissao_id');
    }

    public function perfis()
    {
        return $this->belongsToMany(PerfilModel::class, 'perfil_permissao', 'permissao_id', 'perfil_id');
    }

    
}
