<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContratoModel;

class Contrato extends Controller
{
    public function report($record)
    {
        $pdf = ContratoModel::DownloadPdf($record);
        //dd($pdf);
        return $pdf->stream('contrato.pdf');
    }

}
