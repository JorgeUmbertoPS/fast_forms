<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModeloRespostaTipo extends Model
{
    use HasFactory;

    const RESPOSTA_TIPO_MULTIPLA_ESCOLHA = 1;
    const RESPOSTA_TIPO_ALTERNATIVA = 2;
    const RESPOSTA_TIPO_VALOR = 3;

    protected $table = "modelo_resposta_tipos";

    protected $fillable = [
        "nome",
        "id"
    ];


}
