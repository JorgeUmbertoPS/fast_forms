<?php

namespace Database\Seeders;

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
    }
}
