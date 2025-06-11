<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissaoModel extends Model
{
    use HasFactory;

    protected $table = 'permissoes';

    const PERMISSAO_CLIENTE = 'manipular-clientes';
    const PERMISSAO_USUARIO = 'manipular-usuarios';
    const PERMISSAO_MANIPULAR_PERFIS = 'manipular-perfis';
    const PERMISSAO_MANIPULAR_PERMISSOES = 'manipular-permissoes';
    const PERMISSAO_MANIPULAR_EMPRESAS = 'manipular-empresas';
    const PERMISSAO_CONFIGURAR_QUESTIONARIO = 'configurar-questionarios';
    const PERMISSAO_RESPONDER_QUESTIONARIO = 'responder-questionarios';
    const PERMISSAO_MANIPULAR_FORMULARIOS = 'manipular-formularios';
    const PERMISSAO_MANIPULAR_MODELOS = 'manipular-modelos';
    const PERMISSAO_MANIPULAR_CONFIGURACOES = 'manipular-configuracoes';    


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
