<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Throwable;
use Filament\Panel;
use App\Models\Perfil;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, HasSuperAdmin, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $logName      = 'Usuários';

    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'empresa_id'
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;//$this->hasRole(['Admin']);
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function isAdmin(): bool{
        return $this->hasRole("admin");
    }

    public function isRoot(): bool{
        return $this->hasRole("root");
    }
     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'empresa_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function FormulariosUsers():BelongsToMany{
        return ($this->BelongsToMany(User::class, 'formularios_users', 'form_id'));
    }

    public function setor_user(){
        return $this->belongsTo(Setor::class, 'setor_id');
    }

    public static function trocar_senha($data){
        try {

            if($data['ativo'] == 0){
                return ['status' => false,'mensagem'=> 'Não foi possível trocar a senha. Usuário Bloqueado'];   
            }

            if($data['senha'] !== $data['senha2']){
                return ['status' => false,'mensagem'=> 'As senhas não coincidem'];   
            }            

            if(self::where('id', $data['id'])->update([
                    'password'=> bcrypt($data['senha2'])
                ])
            ){
                return ['status'  => true, 'mensagem' => 'Senha alterada com sucesso'];
            }
            else{
                return ['status' => false,'mensagem'=> 'São foi possível alterar a senha'];  
            }            
        } 
        catch (Throwable $th) 
        {
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100)); 
        }
    }


    public static function HabDesabUser($id){
        try {
            $user = self::find($id);

            if($user->ativo == 0){

                if(self::where('id', $id)->update([
                    'ativo'=> 1
                    ])
                ){
                    return ['return' => true, 'mensagem' => 'Usuário liberado com sucesso'];
                }
                else{
                    return ['return'=> false,'mensagem'=> 'São foi possível liberar usuário'];  
                } 
            }
            else{

                if(self::where('id', $id)->update([
                    'ativo'=> 0
                    ])
                ){
                    return ['return' => true, 'mensagem' => 'Usuário bloqueado com sucesso'];
                }
                else{
                    return ['return'=> false,'mensagem'=> 'São foi possível bloquear usuário'];  
                } 

            }
            
        } 
        catch (Throwable $th) 
        {
            return array('status' => false, 'mensagem' => substr($th->getMessage(),0, 100)); 
        }
    }
    

}
