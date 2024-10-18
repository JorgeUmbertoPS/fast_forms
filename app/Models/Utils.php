<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utils extends Model
{
    public static function CompactaImagem($source, $destination, $quality) {

        $info = getimagesize($source);
        
        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);
        
        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);
        
        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);
        
        imagejpeg($image, $destination, $quality);
        
        return $destination;
    }
    
    public static function ObterMesResumido($mes_int){
        $mes = '';
        switch ($mes_int) {
            case 1:
                $mes = 'Jan';
                break;
            case 2:
                $mes = 'Fev';
                break;
            case 3:
                $mes = 'Mar';
                break;
            case 4:
                $mes = 'Abr';
                break;
            case 5:
                $mes = 'Mai';
                break;
            case 6:
                $mes = 'Jun';
                break;
            case 7:
                $mes = 'Jul';
                break;
            case 8:
                $mes = 'Ago';
                break;
            case 9:
                $mes = 'Set';
                break;
            case 10:
                $mes = 'Out';
                break;
            case 11:
                $mes = 'Nov';
                break;
            case 12:
                $mes = 'Dez';
                break;
        }
        return $mes;
    }
}
