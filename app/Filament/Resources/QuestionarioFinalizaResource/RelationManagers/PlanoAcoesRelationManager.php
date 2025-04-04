<?php

namespace App\Filament\Resources\QuestionarioFinalizaResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\RelationManagers\RelationManager;

class PlanoAcoesRelationManager extends RelationManager
{
    protected static string $relationship = 'plano_acoes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('causa')
                    ->label('Causa')
                    ->required()
                    ->maxLength(255),

                    Forms\Components\TextInput::make('acao_corretiva')
                    ->label('Ação Corretiva')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\DatePicker::make('data_planejada')->label('Data Planejada'),
                    Forms\Components\Select::make('user_id')
                        ->options(User::TodosUsuariosDaEmpresa()->pluck('name', 'id'))
                        ->label('Usuário Responsável'),
                    Forms\Components\Hidden::make('empresa_id')->default(
                        Filament::auth()->user()->empresa_id
                    ),
                    
            ])
            ;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('causa')
            ->columns([
                Tables\Columns\TextColumn::make('causa')->label('Causa'),
                Tables\Columns\TextColumn::make('acao_corretiva')->label('Ação Corretiva'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Novo Plano de Ação'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->gera_plano_acao === true; // ou qualquer condição que você quiser
    }
}
