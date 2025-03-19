<?php

namespace App\Models;

use Filament\Panel;
use App\Models\Team;
use App\Models\Pba\Empresa;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Pba\EmpresaUsuario;
use App\Observers\Pba\UserObserver;
use App\Models\Pba\PlanoContaUsuario;
use App\Models\Pba\CentroCustoUsuario;
use App\Models\Pba\CentroLucroUsuario;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\PermissionRegistrar;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdmin extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable ,HasRoles, SoftDeletes;

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
        
    ];

    protected $table = 'users';

    //use HasSuperAdmin;
    
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
    ];




    public function canAccessPanel(Panel $panel): bool
    {
        //if ($panel->getId() === 'admin') {
            //return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
         //   return true;
        //}
 
        return true;
    }


 
}
