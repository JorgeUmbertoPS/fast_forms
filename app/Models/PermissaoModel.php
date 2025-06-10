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

    // consulta se existe um slug juntando com a tabela perfil_permissoes
    public static function hasPermission($slug)
    {
        if(auth()->user()->perfil_id == PerfilModel::PERFIL_SUPER_ADMIN)
            return true; // Superadmin tem todas as permissões
        
        $permissao_existe = self::where('slug', $slug)
                            ->join('perfil_permissao', 'perfil_permissao.permissao_id', '=', 'permissoes.id')
                            ->join('perfis', 'perfis.id', '=', 'perfil_permissao.perfil_id')
                            ->where('perfil_permissao.empresa_id', auth()->user()->empresa_id)
                            ->where('perfis.id', auth()->user()->perfil_id)
                            ->exists();
                         //   dd($permissao_existe);
        return $permissao_existe ? true : false;
    }
    
}
