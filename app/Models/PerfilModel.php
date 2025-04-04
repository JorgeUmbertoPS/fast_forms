<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilModel extends Model
{
    use HasFactory;

    protected $table = 'perfis';

    protected $fillable = [
        'nome',
        'descricao',
        'perfil_admin',
        'perfil_cliente',
        'empresa_id',
        
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function perfilPermissao()
    {
        return $this->hasMany(PerfilPermissaoModel::class, 'perfil_id');
    }

    public function permissoes()
    {
        return $this->belongsToMany(PermissaoModel::class, 'perfil_permissao', 'perfil_id', 'permissao_id');
    }

    

}
