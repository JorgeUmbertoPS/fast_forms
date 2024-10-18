<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametroSistema extends Model
{
    use HasFactory;

    protected $table = 'parametros';

    protected $fillable = [
        'foto_width',
        'foto_height',
    ];

    public static function getFotoWidth()
    {
        return self::first()->foto_width;
    }

    public static function getFotoHeight()
    {
        return self::first()->foto_height;
    }

    

}
