<?php

namespace App\Traits;

trait TraitErros {

    public static function RetornaErro(\Throwable $th, $len = 35){

        if($len == 0)
            $len = 5000;
        
        return [
                'erro'=> [
                            'mensagem' => substr($th->getMessage(),0, $len) . '...', 
                            'arquivo' => 'Arquivo: ' . basename($th->getFile()) .' - Linha: '. $th->getLine()
                        ]
            ];
       
    }

}