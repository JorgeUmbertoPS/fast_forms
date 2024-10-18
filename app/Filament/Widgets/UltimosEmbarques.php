<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Embarque;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimosEmbarques extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Últimos Embarques';
    public function table(Table $table): Table
    {
        
        return $table
            ->query(
                Embarque::where('tipo', 'N')->where('data', now())->orderBy('data', 'desc')->limit(5)
            )
            ->columns([
                TextColumn::make('clientes.nome')->label('Cliente'),
                TextColumn::make('data')->date('d/m/Y')->label('Data'),
                Tables\Columns\TextColumn::make('total_sacas')->label('Sacas')->numeric(),
                Tables\Columns\TextColumn::make('t_containers')->label('Containers')
                    ->default(function(Embarque $record){
                        return Embarque::total_containers($record->id);
                    })
                    ->numeric(),
            ])->defaultPaginationPageOption(5);
    }
}
