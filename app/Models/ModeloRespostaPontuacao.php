<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ModeloRespostaObserver;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ModeloRespostaPontuacaoItem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ModeloRespostaObserver::class])]
class ModeloRespostaPontuacao extends Model
{
    use HasFactory;

    protected $table = "modelo_respostas_pontuacoes";

    protected $fillable = [
        'id',
        'nome',
        'empresa_id',
        'created_at',
        'updated_at',
    ];

    public function pontuacaoItems():HasMany{
        return $this->hasMany(ModeloRespostaPontuacaoItem::class, 'ponto_id');
    }


}
