<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionario as QuestionarioModel;

class Questionario extends Controller
{
    public function report($record)
    {
        $pdf = QuestionarioModel::DownloadPdf($record);
      
        return $pdf->stream(); // renders the PDF in the browser
    }
}
