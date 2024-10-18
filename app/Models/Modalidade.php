<?php

namespace App\Models;

use App\Traits\TraitLogs;
use App\Models\ModalidadeRoteiro;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Modalidade extends Model
{
    use HasFactory;
    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'modalidades';
    protected $logName      = 'Modalidades';

    protected $fillable = [
        'id', 'nome', 'created_at', 'updated_at', 'texto'
    ];

    public function roteiros(){
        return $this->hasMany(ModalidadeRoteiro::class);
    }

    public function container(){
        return $this->belongsToMany(EmbarqueContainer::class, 'embarques_containers_modalidades', 'modalidade_id');
    }
   
}
