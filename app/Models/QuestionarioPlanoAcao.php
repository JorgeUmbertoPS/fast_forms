<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\QuestionarioPlanoAcaoObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([QuestionarioPlanoAcaoObserver::class])]
class QuestionarioPlanoAcao extends Model
{
    use HasFactory;
    
    protected $table = "questionarios_planos_acao";

    protected $fillable = [
        'id',
        'questionario_id',
        'empresa_id',
        'causa',
        'acao_corretiva',
        'user_id',
        'data_planejada',
        'status',
        'created_at',
        'updated_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_planos_acao.empresa_id', auth()->user()->empresa_id);
        });
    }  


}
