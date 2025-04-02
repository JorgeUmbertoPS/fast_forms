<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuestionarioFinalizaResource\Pages;
use App\Filament\Resources\QuestionarioFinalizaResource\RelationManagers;
use App\Filament\Resources\QuestionarioConfigResource\RelationManagers\PlanoAcoesRelationManager;

class QuestionarioFinalizaResource extends Resource
{
    protected static ?string $model = Questionario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Dados do Questionário')
                    ->description('Dados do Questionário que será finalizado')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                        ->disabled()
                        ->label('Nome do Questionário')
                        ->columnSpan(2),

                        Forms\Components\DatePicker::make('data_inicio')
                            ->label('Data de Início')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('data_termino')
                            ->label('Data de Término')
                            ->disabled()
                            ->columnSpan(1),
                ])->columns(4),

                Section::make('Finalização do Questionário')
                    ->description('Dados do Questionário que será finalizado')
                    ->schema([
                        Forms\Components\RichEditor::make('resumo')
                            ->label('Resumo')
                            ->required()
                            ->placeholder('Resumo'),
                    ])->columns(1),

            ]);
    }


    public static function getRelations(): array
    {
        return [
            PlanoAcoesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionarioFinalizas::route('/'),

            'edit' => Pages\EditQuestionarioFinaliza::route('/{record}/edit'),
        ];
    }
}
