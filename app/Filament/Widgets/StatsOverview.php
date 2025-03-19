<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Cliente;
use App\Models\ModeloFormulario;
use Filament\Forms\Components\Grid;
use App\Models\QuestionarioPergunta;
use App\Models\QuestionarioResposta;
use App\Models\ModeloFormularioEmpresa;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        if(User::SuperAdmin()){
            return [
            

                Stat::make('Modelos', ModeloFormulario::count())
                        ->description('Total de Modelos Cadastrados')
                        ->color('info'),
                Stat::make('Modelos Liberados', ModeloFormulario::where('status',1)->count())
                            ->description('Total de Modelos Liberados para uso')
                            ->color('success'),
                Stat::make('Modelos Não Liberados', ModeloFormulario::where('status',0)->count())
                            ->description('Total de Modelos aguardando liberação')
                            ->color('danger'),
     
                Stat::make('Clientes', Cliente::count())
                            ->description('Total de Cliente Cadastrados')
                            ->color('success'),
    
    
                Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 1)->count())
                            ->description('Total de Formulários em pedido')
                            ->color('success'),
    
                            Stat::make('Formulários', ModeloFormularioEmpresa::where('status',2)->count())
                            ->description('Total de Formulários Rejeitados')
                            ->color('danger'),  
                            
                Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 3)->count())
                            ->description('Total de Formulários aguardando utilização')
                            ->color('success'),                                              
    
                Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 4)->count())
                            ->description('Total de Formulários sendo Utilizados')
                            ->color('success'),                        
       
    
                Stat::make('Formulários', ModeloFormulario::count())
                            ->description('Total de Formulários Cadastrados')
                            ->color('info'),
    
    
            ];    
        }
        else{
            return [
            Stat::make('Modelos', ModeloFormulario::count())
                ->description('Total de Modelos Cadastrados')
                ->color('info'),

            Stat::make('Modelos Liberados', ModeloFormulario::where('status', 1)
                        ->count())
                        ->description('Total de Modelos Liberados para uso')
                        ->color('success'),

            Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 1)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários em pedido')
                        ->color('success'),

                        Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 2)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários Rejeitados')
                        ->color('danger'),  
                        
            Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 3)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários aguardando utilização')
                        ->color('success'),                                              

            Stat::make('Formulários', ModeloFormularioEmpresa::where('status', 4)
                        ->where('empresa_id', auth()->user()->empresa_id)
                        ->count())
                        ->description('Total de Formulários sendo Utilizados')
                        ->color('success'),                        


            Stat::make('Formulários', ModeloFormulario::where('empresa_id', auth()->user()->empresa_id)->count())
                        ->description('Total de Formulários Cadastrados')
                        ->color('info'),   
            ];         
        }

    }
}
