<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaPlano extends Model
{
    use HasFactory;

    protected $table        = 'empresas_planos';

    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  

    protected $fillable = [
        'id',
        'name'
    ];
}
