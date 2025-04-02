<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Role extends Model
{
    use HasFactory;

    public static $roles = [
        'SuperUser', 'Admin', 'User'
    ];
    
    public static $models = [

        'Empresa', 'FormularioPergunta', 'FormularioPerguntaBloco', 
        'FormularioPerguntaTipo', 'FormularioResposta', 'FormularioRespostaPontuacao', 
        'FormularioRespostaPontuacaoItem', 'FormularioRespostaMidia', 'ChkFormulario', 
        'ChkFormularioUser', 'ChkFormularioPergunta', 'ChkFormularioPerguntaBloco', 
        'ChkFormularioPerguntaTipo', 'ChkFormularioPerguntaMidia', 'ChkFormularioPerguntaResposta', 
        'ChkFormularioPerguntaRespostaItem', 'ChkFormularioPerguntaRespostaMidia', 
        'ChkFormularioPerguntaRespostaPontuacao', 'ChkFormularioPerguntaRespostaPontuacaoItem', 'Cliente'
    ];

    public static function updateRoles(){

        if(auth()->user()->hasRole('SuperAdmin')){

            foreach(self::$models as $model){

                if(Permission::where('name', 'Listar ' .$model)->count() == 0){
                    Permission::create(['name' => 'Listar ' .$model, 'guard_name' => 'web']);
                }
                if(Permission::where('name', 'Criar ' .$model)->count() == 0){
                    Permission::create(['name' => 'Criar ' .$model, 'guard_name' => 'web']);
                }
                if(Permission::where('name', 'Editar ' .$model)->count() == 0){
                    Permission::create(['name' => 'Editar ' .$model, 'guard_name' => 'web']);
                }
                if(Permission::where('name', 'Excluir ' .$model)->count() == 0){
                    Permission::create(['name' => 'Excluir ' .$model, 'guard_name' => 'web']);
                }
                if(Permission::where('name', 'Importar '.$model)->count() == 0){
                    Permission::create(['name' => 'Importar ' .$model, 'guard_name' => 'web']);
                }

            }
        }
        
    }
}
