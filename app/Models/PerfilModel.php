<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilModel extends Model
{
    const PERFIL_SUPER_ADMIN = 1;
    const PERFIL_USER_ADMIN = 2;
    const PERFIL_CLIENTE_ADMIN = 3;
    const PERFIL_CLIENTE_USER = 4;

    use HasFactory;

    protected $table = 'perfis';

    protected $fillable = [
        'nome',
        'descricao',
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

    public static function PerfilCliente()
    {
        return 1; // Define o valor do perfil cliente
    }
    
    public static function PerfilAdmin()
    {
        return 0; // Define o valor do perfil admin
    }

}
