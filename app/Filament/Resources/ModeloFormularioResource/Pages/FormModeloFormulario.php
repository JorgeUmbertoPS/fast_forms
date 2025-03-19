<?php

namespace App\Filament\Resources\ModeloFormularioResource\Pages;

use Closure;

use Form\Set;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;

use Filament\Forms\Form;
use App\Models\Formulario;
use Filament\Tables\Table;
use App\Models\ModeloResposta;
use App\Models\ModeloFormulario;
use Filament\Resources\Resource;
use App\Models\ModeloRespostaTipo;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use App\Traits\TraitSomenteSuperUser;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Models\ModeloRespostaPontuacao;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\ModeloFormularioResource\Pages;

class FormModeloFormulario
{

    public static function getComponents()
    {


        return [
            Grid::make()
                ->schema([

                    Forms\Components\Section::make()
                        ->extraAttributes(['class' => 'section-etapas'])
                        ->schema([

                            Forms\Components\Select::make('segmento_id')->label('Segmento')
                                ->relationship(name: 'segmento', titleAttribute: 'nome')
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('nome')
                                    ->label('Segmento')
                                        ->required(),
                                ])
                                ->required(),

                            Forms\Components\TextInput::make('nome')->required()->maxLength(100),                         

                        ])->columns(2),

                        Forms\Components\Section::make("Blocos de Perguntas")
                        ->description('Aqui serão criadas os Blocos de Perguntas para os Formulários')
                        ->icon('heroicon-m-list-bullet')
                        ->extraAttributes(['class' => 'section-etapas'])
                        ->schema([

                            Repeater::make(name:'blocos')->label('')
                            ->relationship('EtapasModelos')
                            ->schema([
                                Forms\Components\Section::make("Cabeçalho do Bloco")
                                ->extraAttributes(['class' => 'section-etapas'])
                                ->description("Utilize para aparecer nos Relatórios")
                                ->schema([

                                    Forms\Components\TextInput::make('descricao')
                                    ->label('Descrição do Bloco')
                                     ->placeholder('Digite a descrição do Bloco')
                                    ->hintColor('warning')
                                    ->required()
                                    ->columnSpan(['lg' => 2])
                                    ->maxLength(100),

                                    Forms\Components\TextInput::make('titulo')
                                        ->label('Título')
                                        ->maxLength(100)
                                        ->placeholder('Digite o nome do Bloco'),

                                    Forms\Components\TextInput::make('sub_titlo')
                                        ->label('Sub Título')
                                        ->placeholder('Digite um Sub Título do Bloco')
                                        ->maxLength(100),

                                    Forms\Components\Textarea::make('instrucoes')
                                        ->label('Instruções')
                                        ->placeholder('Digite as instruções do Bloco'),

                                    Forms\Components\Textarea::make('observacoes')
                                        ->label('Observações')
                                        ->placeholder('Digite as observações do Bloco'),

                                    Forms\Components\Select::make('icon')
                                        ->label('Ícone')
                                        ->required()
                                        ->options([
                                            'book-open' => 'Dados gerais',
                                            'clipboard-document-check' => 'Checklist',
                                            'calendar-days' => 'Calendário',
                                        ])

 
                                ])->columns(2)
                                ->collapsible()->collapsed(),

                                Forms\Components\Section::make("Perguntas")
                                ->description('Aqui serão digitadas as perguntas para coleta de informações')
                                ->icon('heroicon-m-question-mark-circle')
                                ->extraAttributes(['class' => 'section-perguntas'])
                                ->schema([
                                    Repeater::make(name:'perguntas')
                                        ->label('')
                                        ->id('perguntas')
                                    ->relationship('PerguntasEtapasModelo')
                                    ->schema([

                                        Forms\Components\Section::make()
                                        ->extraAttributes(['class' => 'section-perguntas'])
                                        ->schema([

                                            Forms\Components\Select::make(name:'resposta_tipo_id')->label('Tipo de resposta')
                                            ->required()
                                            ->reactive()
                                            ->options(
                                                ModeloRespostaTipo::all()->pluck('nome', 'id')
                                            ),

                                            Select::make('resposta_valor_tipo')
                                            ->label('Tipo de Valor')
                                            ->reactive()
                                            ->required()
                                            ->options([
                                                'text' => 'Texto',
                                                'number' => 'Número',
                                                'date' => 'Data',
                                                'time' => 'Hora'
                                            ])->visible(
                                                function (Get $get) {
                                                    return $get('resposta_tipo_id') == 3;
                                                }
                                            ),
                                            
                                            Forms\Components\TextInput::make('nome')
                                                ->label('Texto da Pergunta ')
                                                ->hint('Texto da Pergunta ou Informação a ser coletada')
                                                ->placeholder('Digite o texto da pergunta')
                                                ->required()
                                                ->live()
                                                ->maxLength(100),

                                                Forms\Components\Section::make()                                                            
                                                ->schema([
                                                    Forms\Components\Checkbox::make('obriga_justificativa')
                                                    ->label('Obriga Justificativa?')
                                                    ->default(0),
                                                    Forms\Components\Checkbox::make('obriga_midia')
                                                    ->label('Obriga Midia?')
                                                    ->default(0),
                                                ])->columns(2),                                                

                                                    Forms\Components\Section::make('Respostas')
                                                    ->description("Aqui serão configuradas as respostas")
                                                    ->extraAttributes(['class' => 'section-respostas'])
                                                    ->schema([
                                                        
                                                        Repeater::make('Resposta')->label('')
                                                        ->relationship('respostasPerguntas')
                                                        ->extraAttributes(['class' => 'section-respostas'])
                                                        ->schema([

                                                            Forms\Components\TextInput::make('nome')
                                                                ->label('Label da Resposta: ')
                                                                ->maxLength(100)
                                                                ->hidden(
                                                                    function (Get $get) {
                                                                        $get('resposta_tipo_id') == null;
                                                                    }
                                                                )
                                                                ->live(),

                                                            Forms\Components\Select::make('icon')
                                                            ->label('Ícone')
                                                            ->required()
                                                            ->options([
                                                                'hand-thumb-down' => 'Resposta Negativa',
                                                                'hand-thumb-up' => 'Resposta Positiva',
                                                                'no-symbol' => 'Resposta Neutra',
                                                            ])                                                                
                                                            
                                                        ])
                                                        ->reorderableWithButtons()
                                                        ->orderColumn('ordem')
                                                        ->addActionLabel('Adicionar Resposta')
                                                        ->columns(2)
                                                        ->defaultItems(1)
                                                        ->collapsible()->collapsed()
                                                        ->itemLabel(fn (array $state): ?string => $state['nome'] ?? null)
                                                        
                                                        
                                                    ])->reactive()
                                                    /* ->visible(
                                                        function (Get $get) {
                                                            
                                                            if($get('resposta_tipo_id') == 3){
                                                                
                                                                return false;
                                                            }
                                                            return true;
                                                        }
                                                    )*/
                                                    
                                        ])

                                    ])->addActionLabel('Adicionar Pergunta')
                                    ->columns(1)
                                    ->cloneable()
                                    ->collapsible()->collapsed()
                                    ->defaultItems(0)
                                    ->reorderableWithButtons()
                                    ->orderColumn('ordem')
                                    ->itemLabel(fn (array $state): ?string => $state['nome'] ?? null)

                                ])

                            ])
                            ->cloneable()
                            ->reorderableWithButtons()
                            ->orderColumn('ordem')
                            ->columns(1)
                            ->collapsible()
                            ->collapsed()
                            ->defaultItems(1)
                            ->itemLabel(fn (array $state): ?string => $state['descricao'] ?? null)
                            ->addActionLabel('Adicionar Blocos')


                        ])
                        ->hidden(fn (Get $get) => $get('id') == null)
                        

            ])];


    }

}