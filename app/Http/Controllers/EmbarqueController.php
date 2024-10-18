<?php
namespace App\Http\Controllers;

use App\Models\Embarque;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class EmbarqueController extends Controller
{

    public function report($record)
    {
        $pdf = Embarque::DownloadPdf($record);
      
        return $pdf->stream(); // renders the PDF in the browser
    }
}
