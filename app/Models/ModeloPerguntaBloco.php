<?php

namespace App\Models;

use App\Models\ModeloPergunta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\ModeloPerguntaBlocoObserver;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ModeloPerguntaBlocoObserver::class])]
class ModeloPerguntaBloco extends Model
{
    use HasFactory;

    protected $table = "modelo_perguntas_blocos";

    protected $fillable = [
        'id',
        'descricao',
        'modelo_id',
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
    


    public function PerguntasEtapasModelo(){
        return $this->hasMany(ModeloPergunta::class, 'bloco_id');
    }


}
