<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Components\MyRepeaterComponent;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuestionarioResource\Pages;
use App\Filament\Resources\QuestionarioResource\RelationManagers\PerguntasRelationManager;
use App\Filament\Resources\QuestionarioResource\RelationManagers\QuestionariosPerguntasRelationManager;
use App\Models\QuestionarioPerguntas;
use Filament\Forms\Components\Checkbox;

class QuestionarioResource extends Resource
{
    protected static ?string $model = Questionario::class;

    protected static ?string $label = 'Questionário';

    protected static ?string $pluralLabel = 'Questionários';

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        $form_id = $form->getRecord();

        return $form
            ->schema([
                Section::make()
                ->schema([
                    Forms\Components\TextInput::make('descricao')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('texto')
                        ->maxLength(255),   
                ]),                 
                Forms\Components\Section::make('Perguntas')  
                ->icon('heroicon-m-check-circle')
                    ->description("Aqui serão cadastrados as Perguntas dos Questionários")      
                    ->schema([                    
                        Repeater::make('perguntas')->label('')
                        ->relationship('questionario_pergunta')
                        ->reorderable(true)
                        ->reorderableWithButtons()
                        ->reorderableWithDragAndDrop(true)
                        ->orderColumn('sequencia')
                        ->schema([ 
                            Section::make('') 
                            ->schema([
                                Select::make('pergunta_imagem')
                                    ->label('Pergunta ou Imagem?')
                                    ->options(['P' => 'Pergunta', 'I' => 'Imagem'])
                                    ->reactive()
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('pergunta')
                                ->required()
                                ->label(
                                    function(Get $get){
                                    return $get('pergunta_imagem') === 'P' ? 'Pergunta' : 'Rótulo da Imagem';
                                    }
                                )
                                ->columnSpan(2)
                                ->maxLength(255),

                                Select::make('pergunta_dependente_id')
                                ->label('Pergunta Dependente')
                                ->options( 
                                    function($record){
                                        return ( QuestionarioPerguntas::obterPerguntasDependentes($record));                                        
                                    }  
                                ), 
                                
                                Checkbox::make('pergunta_neutra')
                                ->label('Pergunta Neutra')
                                ->default(0)
                                ->inline(false)
                                ->columnSpan(1),

                                Checkbox::make('pergunta_finaliza_negativa')
                                ->label('Pergunta Finaliza Negativa')
                                ->default(0)
                                ->inline(false)
                                ->columnSpan(1),

                                Forms\Components\TextInput::make('texto')
                                ->required()
                                ->hidden(
                                    function(Get $get){
                                        return $get('pergunta_imagem') === 'I';
                                    }
                                )
                                ->columnSpan(3)
                                ->maxLength(255),

                            ])->columns(3),


                            Section::make('Dados para Ajuda') 
                            ->description('Aqui são os dados usados para instruir os usuários')
                            ->icon('heroicon-m-question-mark-circle')
                            ->schema([
                                Forms\Components\RichEditor::make('texto_help')
                                      ->label('Texto para ajudar a realizar as fotos')
                                    ->columnSpanFull(),                            
                                         
                            ])->columns(3)->collapsed()                                  
                        ])
                        ->columns(1)
                        ->collapsed()
                        ->collapsible()->collapsed()
                        ->itemLabel(
                            function ($state) {
                                //dd($state);
                                //se o state estiver nulo, retorna null
                                if(!isset($state['sequencia'])){
                                    return null;
                                }
                                else
                                    return $state['sequencia'] .' - '. $state['pergunta'] . ' - ' . substr($state['texto'], 0, 20) . '...' ?? null;
                            })
                            
                        ->addActionLabel('Adicionar Pergunta')
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('count_perguntas')->label('Qtd Perguntas')
                    ->state(
                        function (Questionario $record) {
                            return Questionario::count_perguntas($record->id);
                        }
                    ),                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionarios::route('/'),
            'create' => Pages\CreateQuestionario::route('/create'),
            'edit' => Pages\EditQuestionario::route('/{record}/edit'),
        ];
    }
}
