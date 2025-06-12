<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContratoModel extends Model
{
    use HasFactory;

    protected $table = 'contratos_saas';
    protected $fillable = [
        'contratante_id',
        'software_nome',
        'valor_mensal',
        'data_inicio',
        'observacoes',
        'link_assinatura', // Adicione este campo se necessário
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'valor_mensal' => 'decimal:2',
    ];

    public function contratante()
    {
        return $this->belongsTo(Empresa::class, 'contratante_id');
    }

    public static function DownloadPdf($id)
    {

        $contrato = [

        ];

        // Gera o PDF com a view e os dados passados
        $pdf = PDF::loadView('contratos.gerar-pdf', [
            'contrato' => $contrato,
        ])->setPaper('A4', 'portrait');

        return $pdf;  // Retorna o objeto PDF
    }
}
