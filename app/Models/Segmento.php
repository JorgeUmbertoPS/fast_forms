<?php

namespace App\Models;

use App\Observers\SegmentoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segmento extends Model
{
    use HasFactory;

    protected $table = "segmentos";

    protected $fillable = [
        "nome",
        "status"
    ];
}
