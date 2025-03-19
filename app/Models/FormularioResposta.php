<?php

namespace App\Models;

use App\Observers\FormularioRespostaObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
#[ObservedBy([FormularioRespostaObserver::class])]
class FormularioResposta extends Model
{
    use HasFactory;


    protected $table = "formularios_respostas";

    protected $fillable = [
        'id',
        'pergunta_id',
        'resposta_id',
        'empresa_id',
        'obriga_justificativa',
        'obriga_midia',
        'resposta',
        'pontuacao_id',
        'tipo_dado',
        'created_at',
        'updated_at',
        'nome',
        'icon',
    ];
}
