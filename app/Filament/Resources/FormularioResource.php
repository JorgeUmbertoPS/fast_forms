<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Formulario;
use Filament\Tables\Table;
use App\Models\ModeloMascara;

use App\Models\ModeloFormulario;
use Filament\Resources\Resource;
use App\Models\FormularioPergunta;
use App\Models\ModeloRespostaTipo;
use Illuminate\Support\HtmlString;
use App\Traits\TraitSomenteUsuario;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use App\Models\ModeloRespostaPontuacao;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Filament\Resources\ChkFormularioResource\Pages\EditFormulario;
use App\Filament\Resources\ChkFormularioResource\Pages\ListFormularios;
use App\Filament\Resources\ChkFormularioResource\Pages\CreateFormulario;

class FormularioResource extends Resource
{
    use TraitSomenteUsuario;
    
    protected static ?string $model = Formulario::class;
    protected static ?string $label = 'Formulário';
    protected static ?string $pluralLabel = 'Formulários';
    //protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Configurar Formulários';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {

        return $form
        ->schema([
            Forms\Components\Group::make()
            ->schema([

                Forms\Components\Section::make()
                    ->extraAttributes(['class' => 'section-etapas'])
                    ->schema([

                        Forms\Components\TextInput::make('nome')->label('Descrição')->required()->maxLength(100),
                        Forms\Components\TextInput::make('id')->label('ID')->disabled(),

                    ])->columns(),

                    Forms\Components\Section::make("ETAPAS")
                    ->description('Aqui serão criadas as Etadas para os Formulários')
                    ->icon('heroicon-m-list-bullet')
                    ->extraAttributes(['class' => 'section-etapas'])
                    ->schema([

                        Repeater::make(name:'blocos')->label('')
                        ->relationship('bloco_pergunta')
                        ->schema([
                            Forms\Components\Section::make("Cabeçalho da Etapa")
                            ->extraAttributes(['class' => 'section-etapas'])
                            ->description("Utilize para aparecer nos Relatórios")
                            ->schema([

                                Forms\Components\TextInput::make('titulo')
                                    ->label('Título')
                                    ->maxLength(100)
                                    ->placeholder('Digite o nome da Etapa'),

                                    Forms\Components\TextInput::make('sub_titlo')
                                    ->label('Sub Título')
                                    ->placeholder('Digite um Sub Título da Etapa')
                                    ->maxLength(100),

                                    Forms\Components\TextInput::make('descricao')
                                        ->label('Descrição da Etapa')
                                         ->placeholder('Digite a descrição da Etapa')
                                        ->hintColor('warning')
                                        ->required()
                                        ->columnSpan(['lg' => 2])
                                        ->maxLength(100),

                                        Forms\Components\Textarea::make('instrucoes')
                                        ->label('Instruções')
                                        ->placeholder('Digite as instruções do Bloco'),

                                    Forms\Components\Textarea::make('observacoes')
                                        ->label('Observações')
                                        ->placeholder('Digite as observações do Bloco'),     
                                    
                                    Forms\Components\Select::make('icon')
                                        ->label('Ícone')
                                        ->options(Formulario::GetIcons())
                                        
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
                                ->relationship('perguntasBlocos')
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

                                        Select::make('id_mascara')
                                        ->label('Tipo de Valor')
                                        ->reactive()
                                        ->required()
                                        ->options(
                                            ModeloMascara::all()->pluck('nome', 'id')
                                        )->visible(
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

                                        Forms\Components\Hidden::make('empresa_id')
                                        ->default(
                                            function(){
                                                return auth()->user()->empresa_id;
                                            }
                                        ),
                                        Forms\Components\Hidden::make('user_id')
                                        ->default(
                                            function(){
                                                return auth()->user()->id;
                                            }
                                        ),

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
                                                            ->live(),
                                                        Forms\Components\Select::make('icon')
                                                        ->label('Ícone')
                                                        ->required()
                                                        ->options([
                                                            'hand-thumb-down' => 'Resposta Negativa',
                                                            'hand-thumb-up' => 'Resposta Positiva',
                                                            'no-symbol' => 'Resposta Neutra',
                                                            'check-circle' => 'Somente Check',
                                                        ])    ,
                                                         
                                                    ])
                                                    ->addActionLabel('Adicionar Resposta')
                                                    ->columns(2)
                                                    ->orderColumn('ordem')
                                                    ->collapsible()->collapsed()
                                                    ->itemLabel(fn (array $state): ?string => $state['nome'] ?? null)

                                                 ])->visible(
                                                    function (Get $get) {
                                                        
                                                        if($get('resposta_tipo_id') == 3){
                                                            
                                                            return false;
                                                        }
                                                        return true;
                                                    }) 
                                                
                                    ])

                                ])->addActionLabel('Adicionar Pergunta')
                                ->columns(1)
                                ->collapsible()->collapsed()
                                ->defaultItems(0)
                                ->orderColumn('ordem')
                                ->itemLabel(fn (array $state): ?string => $state['nome'] ?? null)
                                ->mutateRelationshipDataBeforeSaveUsing(function (array $data, Get $get, $record): array {


                                    if($record->form_id == null){
                                        $id = $get('../../id');
                                        $pergunta = FormularioPergunta::find($record->id);
                                        $pergunta->form_id = $id;
                                        $pergunta->save();
                                    }
                                    

                                    return $data;
                                }),
                            ])

                        ])
                        ->cloneable()
                        ->columns(1)
                        ->defaultItems(1)
                        ->orderColumn('ordem')
                        ->itemLabel(fn (array $state): ?string => $state['descricao'] ?? null)
                        ->addActionLabel('Adicionar Etapas')


                    ])
                    ->hidden(fn (Get $get) => $get('id') == 0)
                    

                ]),

 

        ])
        
        ->columns(1);
        



    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->sortable(),                 
                Tables\Columns\TextColumn::make('data_inicio')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('data_termino')
                    ->date('d/m/Y'),
                IconColumn::make('status')
                    ->boolean()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Alterar')
                ->button()
                ->visible(fn($record): bool => $record->status == 1),
                

                Action::make('desativar')
                    ->label(
                        function(Formulario $record){
                            if($record->status == 1) return 'Desativar';
                            else return 'Ativar';
                        }
                    )
                    ->requiresConfirmation()
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')  
                    ->button()                  
                    ->action(fn (Formulario $record) => $record->desativar($record->id)),               

                Action::make('geraQuestionario')
                    ->label('Gerar Questionário')
                    ->requiresConfirmation()
                    ->icon('heroicon-m-cog')
                    ->color('warning')      
                    ->button()     
                    ->visible(fn($record): bool => $record->status == 1)         
                    ->action(function (Formulario $record){

                        $resp = Formulario::GerarQuestionario($record);
                        if($resp['status']== true){
                            Notification::make()
                            ->success()
                            ->title('Gerado com Sucesso')
                            ->body('O questionário foi gerado para ser Respondido')
                            ->send();
                        }
                        else{
                            Notification::make()
                            ->warning()
                            ->title('Questionário não Gerado')
                            ->body('Motivo: ' . $resp['mensagem'])
                            ->send();
                        }
                    }),   
                    
                    Action::make('verView')
                    ->label('Visualizar')
                    ->button()  
                    ->action(function () {
                        // Aqui pode incluir lógica ou apenas redirecionar para a view
                    })
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->modalWidth('80%')
                    ->modalContent(
                        function (Model $record) {
                            $dados = Formulario::ObterDadosView($record->id);
                            return view('filament.modelo.formulario',
                                [
                                    'dados' => $dados,
                                ]
                            );
                        }),
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
            'index' => ListFormularios::route('/'),
            'create' => CreateFormulario::route('/create'),
            'edit' => EditFormulario::route('/{record}/edit'),
        ];
    }

    public function desativar(){
        return true;
    }

}
