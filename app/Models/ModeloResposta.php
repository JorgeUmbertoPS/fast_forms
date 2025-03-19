<?php

namespace App\Models;

use App\Models\ModeloRespostaTipo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ModeloRespostaObserver;
#[ObservedBy([ModeloRespostaObserver::class])]
class ModeloResposta extends Model
{
    use HasFactory;

    protected $table = "modelo_respostas";

    protected $fillable = [
        'id',
        'resposta',
        'nome',
        'pergunta_id',
        'pontuacao_id',
        'obriga_justificativa',
        'obriga_midia',
        'empresa_id',
        'ordem',
        'created_at',
        'updated_at',
        'icon'
        

    ];

    protected $hidden = [
       // 'empresa_id'
    ];

    public function respostasTipo(){
        return $this->hasOne(ModeloRespostaTipo::class);
    }

    public function pontuacao():HasOne
    {
        return $this->hasOne(ModeloRespostaPontuacao::class);
    }
}
