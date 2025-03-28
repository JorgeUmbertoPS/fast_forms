<?php

namespace App\Models;

use App\Models\FormularioResposta;
use App\Observers\FormularioPerguntaObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
#[ObservedBy([FormularioPerguntaObserver::class])]
class FormularioPergunta extends Model
{
    use HasFactory;

    protected $table = "formularios_perguntas";

    protected $fillable = [
        'id',
        'nome',
        'empresa_id',
        'resposta_tipo_id',
        'id_mascara',
        'bloco_id',
        'form_id',
        'modelo_pergunta_id',
        'data_termino',
        'user_id',
        'ordem',
        'created_at',
        'updated_at',
    ];

    public function respostasPerguntas(): HasMany
    {
        return $this->hasMany(FormularioResposta::class, 'pergunta_id');
    }

    protected static function booted(): void
    {
        if(auth()->check() && auth()->user()->empresa_id != null){
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('formularios_perguntas.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('formularios_perguntas.empresa_id', null);
            });
        }

    } 

}
