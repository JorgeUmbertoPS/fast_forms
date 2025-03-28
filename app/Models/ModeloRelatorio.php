<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloRelatorio extends Model
{
    use HasFactory;

    protected $table = "modelos_relatorios";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'modelo',
        'created_at',
        'updated_at'
    ];

    

}
