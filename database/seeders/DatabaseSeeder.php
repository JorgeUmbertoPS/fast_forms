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

        Role::insert([
                ['name' => 'SuperAdmin',  'guard_name' => 'web', 'empresa_id' => 1],
                ['name' => 'UserAdmin',  'guard_name' => 'web', 'empresa_id' => 1],
                ['name' => 'ClienteAdmin', 'guard_name' => 'web', 'empresa_id' => null],
                ['name' => 'ClienteUser',  'guard_name' => 'web', 'empresa_id' => null]
        ]);        

        DB::table('model_has_roles')->insert(['role_id' => '1', 'model_type' => 'App\Models\User', 'model_id' => '1']);
        DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '1']);

        /*DB::table('model_has_roles')->insert(['role_id' => '2', 'model_type' => 'App\Models\User', 'model_id' => '2']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '1']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '2']);
        DB::table('model_has_roles')->insert(['role_id' => '3', 'model_type' => 'App\Models\User', 'model_id' => '3']);*/



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
        
        $models = [
            'Empresas', 'FormularioPergunta', 'FormularioPerguntaBloco', 
            'FormularioPerguntaTipo', 'FormularioResposta', 'FormularioRespostaPontuacao', 
            'FormularioRespostaPontuacaoItem', 'FormularioRespostaMidia', 'ChkFormulario', 
            'ChkFormularioUser', 'ChkFormularioPergunta', 'ChkFormularioPerguntaBloco', 
            'ChkFormularioPerguntaTipo', 'ChkFormularioPerguntaMidia', 'ChkFormularioPerguntaResposta', 
            'ChkFormularioPerguntaRespostaItem', 'ChkFormularioPerguntaRespostaMidia', 
            'ChkFormularioPerguntaRespostaPontuacao', 'ChkFormularioPerguntaRespostaPontuacaoItem'
        ];

        foreach($models as $model){
            Permission::create(['name' => 'Listar '  .$model, 'guard_name' => 'web']);
            Permission::create(['name' => 'Criar '   .$model, 'guard_name' => 'web']);
            Permission::create(['name' => 'Editar '  .$model, 'guard_name' => 'web']);
            Permission::create(['name' => 'Excluir ' .$model, 'guard_name' => 'web']);
            Permission::create(['name' => 'Importar '.$model, 'guard_name' => 'web']);
        }

        // pegar todas as roles que o empresa_id é null e criar todas as permissoes em role_has_permissions
        $roles = Role::where('empresa_id', null)->get();
        foreach($roles as $role){
            $permissions = Permission::all();
            $role->syncPermissions($permissions);
        }
    }
}
