<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\QuestionarioUserObserver;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([QuestionarioUserObserver::class])]
class QuestionarioUser extends Model
{
    use HasFactory;
    protected $table = "questionarios_users";

    protected $fillable = [
        'id',
        'form_id',
        'user_id',
        'empresa_id',
        'user_tipo',
        'created_at',
        'updated_at',
        'recebe_email',
    ];

    protected $casts = [

    ];

    public function usuarios():HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected static function booted(): void
    {
        if(auth()->check() && auth()->user()->empresa_id != null){
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_users.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_users.empresa_id', null);
            });
        }

    }      

    
}
