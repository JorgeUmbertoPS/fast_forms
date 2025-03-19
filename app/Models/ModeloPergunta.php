<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ModeloPerguntaObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ModeloPerguntaObserver::class])]
class ModeloPergunta extends Model
{
    use HasFactory;

    protected $table = "modelo_perguntas";

    //incementa o id
    public $incrementing = true;

    //timestamps
    public $timestamps = true;

    protected $fillable = [
        'id',
        'nome',
        'resposta_tipo_id',
        'descricao',
        'resposta_valor_tipo',
        'empresa_id',
        'created_at',
        'updated_at',
        'bloco_id',
        'modelo_id',
        'created_at',
        'updated_at',
        'ordem',
        'pontuacao_id',
        'obriga_justificativa',
        'obriga_midia',
        'icon'

    ];

    protected $casts = [

    ];

    protected $hidden = [
        'empresa_id'
    ];

    protected $dates = ['created_at','updated_at'];

    public function respostasPerguntas(): HasMany
    {
        return $this->hasMany(ModeloResposta::class, 'pergunta_id');
    }


}
