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
        return $this->HasMany(PerfilPermissaoModel::class, 'perfil_id', 'id');

    }

    public function permissoes()
    {
        return $this->belongsToMany(PermissaoModel::class, 'perfil_permissao', 'perfil_id', 'permissao_id')
            ->withPivot('empresa_id')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->hasMany(User::class, 'perfil_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    

}
