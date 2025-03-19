<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloRespostaPontuacaoItem extends Model
{
    use HasFactory;


    protected $table = "modelo_respostas_pontuacoes_items";

    protected $fillable = [
        'id',
        'nome',
        'empresa_id',
        'ponto_id',
        'valor',
        'cor',
        'created_at',
        'updated_at',
    ];


}
