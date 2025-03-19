<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Observers\QuestionarioPerguntaBlocoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([QuestionarioPerguntaBlocoObserver::class])]
class QuestionarioPerguntaBloco extends Model
{
    use HasFactory;

    protected $table = "questionarios_perguntas_blocos";

    protected $fillable = [
        'id',
        'questionario_id',
        'empresa_id',
        'titulo',
        'sub_titulo',
        'descricao',
        'ordem',
        'created_at',
        'updated_at',
        'status',
        'formulario_bloco_id',
        'modelo_bloco_id',
        'instrucoes',
        'observacoes',
        'qtd_colunas',
        'icon',
        
    ];

    protected static function booted(): void
    {
        if(auth()->check()){
            static::addGlobalScope('empresas', function (Builder $query) {
                    $query->where('questionarios_perguntas_blocos.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_perguntas_blocos.empresa_id', 0);
            });
        }
    }    

    public function perguntas_questionario(){
        return $this->hasMany(QuestionarioPergunta::class, 'bloco_id', 'id')->orderBy('ordem')->with('questionario_respostas');
    }



}
