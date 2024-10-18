<?php

namespace App\Filament\Widgets;

use App\Models\Embarque;
use Filament\Widgets\ChartWidget;

class TimeLineMensalChart extends ChartWidget
{

    protected static ?string $heading = 'Quantidade de Embarques Mensal - Ano Atual';

    //full
    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';
 
    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Embarques',
                    'data' => Embarque::QuantidadeAnual(),
                ],
            ],
            'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
