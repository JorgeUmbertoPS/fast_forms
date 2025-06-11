<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Questionario;
use App\Models\ModeloFormulario;
use Filament\Forms\Components\Grid;
use App\Models\QuestionarioPergunta;
use App\Models\QuestionarioResposta;
use App\Models\ModeloFormularioEmpresa;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Indicadores';

    protected ?string $description = 'Visialização das principais métricas';

    protected function getStats(): array
    {

            return [

            Stat::make('Modelos', ModeloFormulario::count())
                        ->description('Total de Modelos Cadastrados')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-bookmark')
                        ->color('info'),

            Stat::make('Modelos Liberados', ModeloFormulario::where('status', 1)
                        ->count())
                        ->description('Total de Modelos Liberados para uso')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-bookmark')
                        ->color('success'),

            Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 1)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários em pedido')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-calendar-days')
                        ->color('success'),

                        Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 2)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários Rejeitados')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-calendar-days')
                        ->color('danger'),  
                        
            Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 3)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários aguardando utilização')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-calendar-days')
                        ->color('success'),                                              

            Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 4)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários sendo Utilizados')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-calendar-days')
                        ->color('success'),                        


            Stat::make('Formulários', ModeloFormulario::where('empresa_id', auth()->user()->empresa_id)->count())
                        ->description('Total de Formulários Cadastrados')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-calendar-days')
                        ->color('info'),   


            Stat::make('Questionários', Questionario::count())
                        ->description('Total de Questionários Cadastrados')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('info'),

            Stat::make('Questionários', Questionario::where('status', 1)->count())
                        ->description('Total de Questionários Abertos')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('warning'),       
                        
                Stat::make('Questionários', Questionario::where('status', '<>', 1)->count())
                        ->description('Total de Questionários Finalizados')
                        ->extraAttributes(['class' => 'text-center'])
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('success'),                          
            ];         
        

    }

    public static function canView(): bool
    {
        return User::AdminEmpresa();

    }

    
}
