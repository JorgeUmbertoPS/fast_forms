<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloMascara extends Model
{
    use HasFactory;

    protected $table = "modelos_mascaras";

    public $incrementing = true;

    protected $fillable = [
        'nome',
        'mascara',
    ];


}
