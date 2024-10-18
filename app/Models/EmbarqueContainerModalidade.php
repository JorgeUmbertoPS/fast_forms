<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmbarqueContainerModalidade extends Model
{
  
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public $incrementing    = true;  
    protected $table        = 'embarques_containers_modalidades';

    protected $fillable = [
        'id',
        'container_id',
        'modalidade_id',
        ];
}
