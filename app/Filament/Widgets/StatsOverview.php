<?php

namespace App\Filament\Widgets;

use App\Models\Embarque;
use App\Models\EmbarqueContainer;
use App\Models\ModeloFormulario;
use App\Models\QuestionarioPergunta;
use App\Models\QuestionarioResposta;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('', Embarque::count())
            ->description('Total de Embarques')
            ->color('success')
            ->extraAttributes(['class' => 'fi-stats-text' ]),
            Stat::make('', Embarque::EmAndamento())
            ->description('Embarques em Andamento')
            ->color('success')
            ->extraAttributes(['class' => 'fi-stats-text' ]), 
            Stat::make('', Embarque::Finalizados())
            ->description('Embarques Finalizados')
            ->color('success')
            ->extraAttributes(['class' => 'fi-stats-text' ]),
            Stat::make('', EmbarqueContainer::QuantidadeReprovada())
            ->description('Qtd de Containers Reprovados')
            ->color('danger')
            ->extraAttributes(['class' => 'fi-stats-text' ]),

        ];
    }
}
