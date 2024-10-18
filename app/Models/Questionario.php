<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitLogs;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionario extends Model
{

    use HasFactory;

    use TraitLogs;
   
    protected $primaryKey   = 'id';
    public $timestamps      = true;
    public $incrementing    = true;  
    protected $logName      = 'Questionários';

    protected $table        = 'questionarios';
    protected $fillable = [
        'id', 'descricao', 'created_at', 'updated_at', 'texto'
    ];

    public function questionario_pergunta(){
        return $this->hasMany(QuestionarioPerguntas::class);
    }

    public static function count_perguntas($id){
        return QuestionarioPerguntas::where('questionario_id', $id)->count();
    }

    public static function count_modalidades($id){
        return ModalidadeRoteiro::where('modalidade_id', $id)->count();
    }

    public static function count_lacres($id){
        return LacracaoRoteiro::where('lacre_id', $id)->count();
    }    
    

    public static function GetDescricao($id){
        return self::where('id', $id)->first()['descricao'] ?? null;
    }


}
