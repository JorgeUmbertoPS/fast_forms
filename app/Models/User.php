<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Perfil;
use Nette\Utils\Random;

use App\Observers\UserObserver;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'empresa_id',
        'admin_cliente',
        'admin_empresa',
        'perfil_id',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;//$this->hasRole(['Admin']);
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'ativo' => 'boolean',
        'admin' => 'boolean'
    ];


    public function FormulariosUsers():BelongsToMany{
        return ($this->BelongsToMany(User::class, 'formularios_users', 'form_id'));
    }

    public static function TodosUsuariosDaEmpresa(){
        return static::where('empresa_id','=',auth()->user()->empresa_id)->get();
    }

    public static function SuperAdmin():bool{
        $user = User::find(auth()->user()->id);
        return $user->perfil_id == PerfilModel::PERFIL_SUPER_ADMIN;
    }

    public static function AdminEmpresa():bool{
        $user = User::find(auth()->user()->id);
        return $user->admin_empresa == 1;
    }

    public function empresa_has_one(){
        return $this->hasOne(Empresa::class, 'id', 'empresa_id');
    }


    public static function SendMail($record){
        try {
            
            DB::beginTransaction();
            $user = User::find($record->id);
            $user->password = Hash::make(Random::generate(8));

            $token = app('auth.password.broker')->createToken($user);
            $notification = new \Filament\Notifications\Auth\ResetPassword($token);
            $notification->url = \Filament\Facades\Filament::getResetPasswordUrl($token, $user);
            $user->notify($notification);

            Notification::make()
                    ->title('Reset de Senha')	
                    ->iconColor('success')
                    ->color('success') 
                    ->body('Email enviado com sucesso para '.$user->email)
                    ->send();
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            Notification::make()
            ->title('Reset de Senha')	
            ->iconColor('danger')
            ->color('danger') 
            ->body('Erro ao enviar email para '.$user->email. ' - '.$th->getMessage())
            ->send();
        }
    }



}
