<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use App\Models\Empresa;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionarioPergunta;
use App\Models\QuestionarioResposta;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class UltimasPerguntasRespondidas extends BaseWidget
{

    protected int | string | array $columnSpan = 2;

    protected static ?string $heading = 'Utilizados pelos Clientes';

    public function table(Table $table): Table
    {
        if(User::SuperAdmin()){
            return $table
                ->query(

                    Empresa::leftJoin('modelo_formularios_empresas', 'modelo_formularios_empresas.empresa_id', '=', 'empresas.id')
                    ->leftJoin('formularios', 'formularios.empresa_id', '=', 'empresas.id')
                    ->leftJoin('questionarios', 'questionarios.empresa_id', '=', 'empresas.id')
                    ->select('empresas.nome', 'empresas.id',
                        DB::raw('(select count(*) from modelo_formularios_empresas where modelo_formularios_empresas.empresa_id = empresas.id) qtd_modelos_utilizados'),
                        DB::raw('(select count(*) from formularios f where f.empresa_id = empresas.id) qtd_formularios_gerados'),
                        DB::raw('(select count(*) from questionarios q where q.empresa_id = empresas.id) qtd_questionarios_gerados'))
                    ->groupBy('empresas.nome', 'empresas.id'))
                ->columns([
                    TextColumn::make('nome')->label('Empresa'),
                    TextColumn::make('qtd_modelos_utilizados')->label('Modelos Utilizados'),
                    TextColumn::make('qtd_formularios_gerados')->label('Formulários Gerados'),
                    TextColumn::make('qtd_questionarios_gerados')->label('Questionários Gerados'),
                    
                ]);
        }
        else
        {
            return $table
                ->query(

                    Empresa::leftJoin('modelo_formularios_empresas', 'modelo_formularios_empresas.empresa_id', '=', 'empresas.id')
                    ->leftJoin('formularios', 'formularios.empresa_id', '=', 'empresas.id')
                    ->leftJoin('questionarios', 'questionarios.empresa_id', '=', 'empresas.id')
                    ->where('modelo_formularios_empresas.empresa_id', auth()->user()->empresa_id)
                    ->select('empresas.nome', 'empresas.id',
                        DB::raw('(select count(*) from modelo_formularios_empresas where modelo_formularios_empresas.empresa_id = empresas.id) qtd_modelos_utilizados'),
                        DB::raw('(select count(*) from formularios f where f.empresa_id = empresas.id) qtd_formularios_gerados'),
                        DB::raw('(select count(*) from questionarios q where q.empresa_id = empresas.id) qtd_questionarios_gerados'))
                    ->groupBy('empresas.nome', 'empresas.id'))
                ->columns([
                    TextColumn::make('nome')->label('Empresa'),
                    TextColumn::make('qtd_modelos_utilizados')->label('Modelos Utilizados'),
                    TextColumn::make('qtd_formularios_gerados')->label('Formulários Gerados'),
                    TextColumn::make('qtd_questionarios_gerados')->label('Questionários Gerados'),
                    
                ]);
        }
    }
}
