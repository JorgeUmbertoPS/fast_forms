<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Observers\FormularioPerguntaBlocoObserver;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
#[ObservedBy([FormularioPerguntaBlocoObserver::class])]
class FormularioPerguntaBloco extends Model
{
    use HasFactory;

    protected $table = "formularios_perguntas_blocos";

    protected $fillable = [
        'id',
        'descricao',
        'form_id',
        'empresa_id',
        'ordem',
        'created_at',
        'updated_at',
        'sigla',
        'titulo',
        'sub_titulo',
        'observacoes',
        'instrucoes',
        'icon',

    ];


    public function perguntasBlocos(): HasMany
    {
        return $this->hasMany(FormularioPergunta::class, 'bloco_id');
    }

}
