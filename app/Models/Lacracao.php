<?php

namespace App\Models;

use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lacracao extends Model
{
    use HasFactory;

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $table        = 'lacracoes';
    protected $logName      = 'Lacrações';

    protected $fillable = [
        'id', 
        'descricao', 
        'created_at', 
        'updated_at', 
        'texto'
    ];

    public function lacracoes_roteiros(){
        return $this->hasMany(LacracaoRoteiro::class, 'lacracao_id', 'id' );
    }
}
