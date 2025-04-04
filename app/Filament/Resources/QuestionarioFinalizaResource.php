<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use App\Filament\Resources\QuestionarioFinalizaResource\Pages;
use App\Filament\Resources\QuestionarioFinalizaResource\RelationManagers\PlanoAcoesRelationManager;


class QuestionarioFinalizaResource extends Resource
{
    protected static ?string $model = Questionario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Inconsistências')
                ->description('Inconsistências que precisam ser resolvidas antes de finalizar o questionário')
                ->icon('heroicon-o-exclamation-triangle')
                ->iconColor('danger')
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\ViewField::make('inconsistencias')
                                ->view('filament.pages.questionario-finaliza-inconsistencias')
                                ->label('Inconsistências')
                                ->afterStateHydrated(function ($component, $record) {
                                    $inconsistencias = [];
                                    $inconsistencias = Questionario::InconsistenciasAntesDeFechar($record);
                                    $component->state($inconsistencias);
                                })
                                ->columnSpan(4),
                        ])->columns(1),

                ])
                ->visible(
                    fn (callable $get) => $get('inconsistencias') != null
                )
                ->columns(4),

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

                        // toogle com status
                        Forms\Components\Toggle::make('status')
                            ->label('Status')
                            ->columnSpan(1)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(),
                ])->columns(4),



                Section::make('Finalização do Questionário')
                    ->description('Dados do Questionário que será finalizado')
                    ->schema([
                        Forms\Components\RichEditor::make('resumo')
                            ->label('Resumo')
                            ->required(
                                fn (callable $get) => $get('criar_resumo') == true
                            )
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
