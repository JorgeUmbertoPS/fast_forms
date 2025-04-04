<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Segmento;
use Illuminate\Database\Seeder;
use App\Models\ModeloRespostaTipo;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\ModeloRespostaPontuacao;
use Spatie\Permission\Models\Permission;
use App\Models\ModeloRespostaPontuacaoItem;
use App\Models\PerfilModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Empresa::insert([
            'nome' => "FastForms",
            'status' => 1,
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);

        User::insert([
            "name" => "SuperAdmin",
            'email' => "superadmin@fastforms.com.br",
            'email_verified_at' => null,
            "password" => Hash::make('admin123'),
            'empresa_id' => 1,
            'ativo' => 1,
            'remember_token' => NULL,
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);

        $user = User::find(1);
        Auth::login($user);

        /*
        Empresa::insert([
            'nome' => "Cliente1 1",
            'status' => 1,
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);*/

        ModeloRespostaPontuacao::insert([
            'nome' => "Semáforo",
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);


        ModeloRespostaPontuacaoItem::insert([
            'nome' => "Vermelho",
            'ponto_id' => 1,
            'valor' => 1,
            'cor' => 'rgb(255, 0, 0)',
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);

        ModeloRespostaPontuacaoItem::insert([
            'nome' => "Amarelo",
            'ponto_id' => 1,
            'valor' => 3,
            'cor' => 'rgb(255, 255, 0)',
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);

        ModeloRespostaPontuacaoItem::insert([
            'nome' => "Verde",
            'ponto_id' => 1,
            'valor' => 6,
            'cor' => 'rgb(36, 255, 0)',
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);

        ModeloRespostaTipo::insert(['nome' => 'Multipla Escolha', 'componente' => 'CheckBox']);
        ModeloRespostaTipo::insert(['nome' => 'Alternativa',      'componente' => 'RadioButton']);
        ModeloRespostaTipo::insert(['nome' => 'Valor',            'componente' => 'TextBox']);

        PerfilModel::insert([
                ['nome' => 'Super Admin',  'descricao' => 'Administrador FastForms', 'empresa_id' => 1, 'perfil_admin' => 1, 'perfil_cliente' => 0],
                ['nome' => 'User Admin',  'descricao' => 'Usuário FastForms', 'empresa_id' => 1, 'perfil_admin' => 1, 'perfil_cliente' => 0],
        ]);        

        User::insert([
            "name" => "Admin",
            'email' => "admin@fastforms.com.br",
            'email_verified_at' => null,
            "password" => Hash::make('admin123'),
            'empresa_id' => 1,
            'ativo' => 1,
            'remember_token' => NULL,
            'created_at' => Date("Y-m-d H:i:s"),
            'updated_at' => Date("Y-m-d H:i:s"),
        ]);  
        

    }
}
