<?php

namespace App\Models;

use App\Models\User;
use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use App\Observers\EmpresaUserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([EmpresaUserObserver::class])]
class EmpresaUsuario extends Model
{
    use HasFactory, TraitLogs;

    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'empresas_users';

    public $modelName = 'Empresas Usuários';
    protected $fillable = [
        'id',
        'empresa_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function preencherEmpresas($record){
        $user_id = $record->id;
        $empresas = Empresa::where('id', auth()->user()->empresa_id)->get();
        foreach($empresas as $empresa){
            $empresaUsuario = EmpresaUsuario::where('user_id', $user_id)->where('empresa_id', $empresa->id)->first();
            if(!$empresaUsuario){
                $empresaUsuario = new EmpresaUsuario();
                $empresaUsuario->user_id = $user_id;
                $empresaUsuario->empresa_id = $empresa->id;
                $empresaUsuario->save();
            }
        }
    }
}
