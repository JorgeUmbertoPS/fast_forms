<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    protected static function booted(): void
    {
        // SE USUARIO ESTIVER LOGADO
        if(auth()->check()){

            static::addGlobalScope('empresas', function (Builder $query) {
                    $query->where('contratos_saas.empresa_id', auth()->user()->empresa_id);
            });
        }
        else{
            static::addGlobalScope('empresas', function (Builder $query) {
                $query->where('contratos_saas.empresa_id', 0);
            });
        }
    }    

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
