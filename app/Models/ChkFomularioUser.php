<?php

namespace App\Models;

use App\Models\User;
use App\Models\Formulario;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ChkFomularioUserObserver;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
#[ObservedBy([ChkFomularioUserObserver::class])]
class ChkFomularioUser extends Model
{
    use HasFactory;

    protected $table = "formularios_users";

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




}
