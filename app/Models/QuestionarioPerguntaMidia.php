<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionarioPerguntaMidia extends Model
{
    protected $table = 'questionarios_perguntas_midias';
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('questionarios_perguntas_midias.empresa_id', auth()->user()->empresa_id);
        });
    }  
}


