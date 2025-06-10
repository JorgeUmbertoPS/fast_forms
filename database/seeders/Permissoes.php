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
        //
        $models = [
            'clientes', 'usuarios', 'modelos', 'formularios', 'configurar_questionarios', 'responder_questionarios'
        ];
        foreach ($models as $model) {
            PermissaoModel::create([
                'nome' => ucfirst($model),
                'descricao' => 'Permissão para o módulo ' . ucfirst($model),
                'slug' => 'manipular-' . $model,
            ]);
        }

        // incluir permissoes para o perfil de cliente perfil_permissao 
        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'manipular-clientes')->first()->id,
        ]);
        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'manipular-usuarios')->first()->id,
        ]);
        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'manipular-formularios')->first()->id,
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'configurar_questionarios')->first()->id,
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'responder_questionarios')->first()->id,
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 3, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'manipular-modelos')->first()->id,
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 4, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'configurar_questionarios')->first()->id,
        ]);

        PerfilPermissaoModel::insert([
            'perfil_id' => 4, // ID do perfil de cliente
            'permissao_id' => PermissaoModel::where('slug', 'responder_questionarios')->first()->id,
        ]);
    }
}
