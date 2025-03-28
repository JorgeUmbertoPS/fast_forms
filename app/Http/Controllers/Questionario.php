<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionario as QuestionarioModel;

class Questionario extends Controller
{
    public function report($record)
    {
        $pdf = QuestionarioModel::DownloadPdf($record);
        //dd($pdf);
        return $pdf->stream('questionario.pdf');
    }

}
