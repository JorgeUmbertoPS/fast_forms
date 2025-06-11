<?php

namespace Database\Seeders;

use App\Models\PerfilPermissaoModel;
use App\Models\PermissaoModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Permissoes extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {

        // incluir permissoes para o perfil de cliente perfil_permissao 
        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_CLIENTE)->first()->id,
            'empresa_id' => 2, // ID da empresa, se necessário
        ]);
        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_USUARIO)->first()->id,
            'empresa_id' => 2
        ]);
        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_MANIPULAR_FORMULARIOS)->first()->id,
            'empresa_id' => 2
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_CONFIGURAR_QUESTIONARIO)->first()->id,
            'empresa_id' => 2
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_RESPONDER_QUESTIONARIO)->first()->id,
            'empresa_id' => 2
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_MANIPULAR_MODELOS)->first()->id,
            'empresa_id' => 2
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 4, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_CONFIGURAR_QUESTIONARIO)->first()->id,
            'empresa_id' => 2
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 4, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', PermissaoModel::PERMISSAO_RESPONDER_QUESTIONARIO)->first()->id,
            'empresa_id' => 2
        ]);
    }
}
