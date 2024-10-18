<?php

namespace App\Filament\Resources\EmbarqueResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Modalidade;
use App\Models\Questionario;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ContainersRelationManager extends RelationManager
{
    protected static string $relationship = 'containers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('container')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('questionario_id')
                    ->options(Questionario::pluck('descricao','id')),                    
                Forms\Components\Select::make('modalidades')
                ->relationship('modalidade')  
                ->options(Modalidade::pluck('nome','id'))
                ->multiple(),

              
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('container')
            ->columns([
                Tables\Columns\TextColumn::make('container'),
                Tables\Columns\TextColumn::make('modalidade.nome')->label('Modalidades'),
                Tables\Columns\TextColumn::make('questionarios_containers.nome')->label('Questionário'),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    
}
