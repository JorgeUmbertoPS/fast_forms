<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cliente;
use Filament\Forms\Get;
use App\Models\Embarque;
use App\Models\Lacracao;
use Filament\Forms\Form;
use App\Models\Modalidade;
use Filament\Tables\Table;
use App\Models\Questionario;
use App\Models\Transportadora;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Models\EmbarqueContainer;
use Pages\ListEmbarqueContainers;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Mail;
use App\Filament\Pages\CheckListPage;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use App\Models\EmbarqueContainerModalidade;
use App\Filament\Components\MyRepeaterComponent;
use App\Models\EmbarqueContainerLacracaoResposta;
use App\Filament\Resources\EmbarqueResource\Pages;
use App\Models\EmbarqueContainerChecklistResposta;
use App\Models\EmbarqueContainerModalidadeResposta;
use App\Filament\Resources\EmbarqueResource\RelationManagers\QuestionariosContainer;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;

class EmbarqueResource extends Resource
{
    protected static ?string $model = Embarque::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Dados do Embarque')
                            ->icon('heroicon-m-truck')
                            ->schema([
                                
                                Grid::make('')
                                ->schema([
                                    
                                    Forms\Components\Select::make('tipo')
                                    ->label('Tipo de Embarque')
                                    ->options(
                                        [
                                            'N' => 'Embarque Diário',
                                            'A' => 'Adiantamento de Embarque'
                                        ]
                                    )
                                    ->native(false)
                                    ->reactive()
                                    ->default('N'),

                                    Forms\Components\Select::make('cliente_id')
                                    ->label('Cliente')
                                    ->required()
                                    ->options(Cliente::all()->pluck('nome', 'id'))
                                    ->searchable(),
                                ])->columns(2),

                                Grid::make('')
                                    ->schema([                                    
                                       
                                    Forms\Components\TextInput::make('contrato')
                                    ->required()
                                    ->maxLength(255)
                                    ->required(),        
                                
    
                                    Forms\Components\Select::make('transportadora_id')
                                    ->label('Transportadora')
                                    ->options(Transportadora::all()->pluck('nome', 'id'))
                                    ->required()
                                    ->searchable(),                            
            
                                    Forms\Components\DatePicker::make('data'),
        
                                    Forms\Components\TextInput::make('booking')
                                    ->maxLength(255),     
        
                                    Forms\Components\TextInput::make('navio')
                                    ->maxLength(255),   

                                    Forms\Components\Textarea::make('embalagens'),                                                                        
        
                                    Forms\Components\TextInput::make('total_sacas')->label('Total de Sacas')
                                        ->required(fn ($get) => $get('tipo') == 'N')
                                        ->numeric(),     
                                    //status
                                    Forms\Components\Select::make('status_embarque')
                                    ->label('Status do Embarque')
                                    ->options(
                                        [
                                            'L' => 'Liberado',
                                            'B' => 'Bloqueado',
                                            'F' => 'Finalizado'
                                        ]
                                    )->disabled(true)                                                 
                                ])->columns(2),
        
                            ]),
                            
                        Tabs\Tab::make('Containers')
                            ->icon('heroicon-m-table-cells')
                            ->badge(
                                
                                function(?Embarque $record){
                                    if(isset($record->id))
                                        return Embarque::total_containers($record->id);
                                    else
                                        return 0;
                                }
                            )
                            ->schema([
                                Grid::make('')
                                ->schema([
        
                                    MyRepeaterComponent::make('containers')->label('')
                                    ->relationship('containers_embarques') 
                                    ->schema([ 
                                        Hidden::make('id')->visibleOn('edit'),

                                        Tabs::make('Tabs')
                                        ->tabs([
                                            Tabs\Tab::make('Dados do Container')
                                                ->schema([
   

                                                    Forms\Components\Actions::make([
                                                        Forms\Components\Actions\Action::make('gerar_questionario')->label('Gerar Quentionário para Container')
                                                            ->action(

                                                                function (EmbarqueContainer $record ) {
                                                                        $ret = EmbarqueContainer::GerarCheklistParaUmContainer($record);
                                                                    
                                                                        if($ret['status'] == false){
                                                                            Notification::make()
                                                                            ->title('Problemas ao Gerar Chacklist')	
                                                                            ->iconColor('danger')
                                                                            ->color('danger')   
                                                                            ->body($ret['mensagem'])
                                                                            ->send();
                                                                        }
                                                                        else{
                                                                            Notification::make()
                                                                            ->title('Gerado com Sucesso')	
                                                                            ->iconColor('success')
                                                                            ->color('success')   
                                                                            ->body($ret['mensagem'])
                                                                            ->send();
                                                                        }
                                                                    }
                                                                    
                                                            )->requiresConfirmation()
                                                            ->modalHeading('Gerar Checklist, Modalidades e Lacração')
                                                            ->modalDescription('Serão geradas respostas para o Checklist, Modalidades e Lacração para esse Container. Deseja continuar?')
                                                            ->modalSubmitActionLabel('Sim, estou ciente')
                                                            ->modalIcon('heroicon-o-question-mark-circle')
                                                            ->icon('heroicon-o-question-mark-circle')
                                                            ->modalIconColor('danger')
                                                            ->color('danger'),
                                                    ])->visible(
                                                        function($record){
                                                            if($record)
                                                                return EmbarqueContainer::ContainerLiberadoGerouQuestionario($record);
                                                            else
                                                                return false;
                                                        }
                                                    ),




                                                    Forms\Components\TextInput::make('container')
                                                    ->required()
                                                    ->maxLength(255),
            
                                                    Forms\Components\Select::make('questionario_id')
                                                    ->required()
                                                    ->options(Questionario::pluck('descricao','id')),  
            
                                                    Forms\Components\Select::make('modalidades')
                                                    ->relationship('modalidade')  
                                                    ->options(Modalidade::pluck('nome','id'))
                                                    ->required()
                                                    ->multiple(),
            
                                                    Forms\Components\Select::make('lacracao_id')
                                                    ->label('Sequencia de Lacração')
                                                    ->required()
                                                    ->options(Lacracao::pluck('descricao','id')), 

                                                    Forms\Components\TextInput::make('oic')
                                                        ->label('OICs'),

                                                    Forms\Components\TextInput::make('lotes')
                                                        ->label('Lotes'),

                                                ]),
                                            Tabs\Tab::make('Checklist Gerado')
                                                ->schema([
                                                    Grid::make()
                                                    ->schema([
                                                    Forms\Components\Actions::make([
                                                        Forms\Components\Actions\Action::make('questionario')
                                                            ->label('Questionário')
                                                            ->badge(
                                                                static function($record){
                                                                    if($record)
                                                                        return EmbarqueContainerChecklistResposta::total_itens($record->id)['total_itens'];
                                                                    else
                                                                        return 0;
                                                                }
                                                            )
                                                            ->button()
                                                            ->color('warning')
                                                            ->url(
                                                                function($record){
                                                                    if($record){
                                                                        return EmbarqueContainerChecklistRespostaResource::getUrl('index', 
                                                                        [
                                                                            'embarques_containers_id' => $record->id, 
                                                                            'embarque_id' => $record->embarque_id,
                                                                        ]);
                                                                    }
                                                                    else
                                                                        return null;
                                                                })                                                            

                                                    ]),  
                                                    Forms\Components\Actions::make([
                                                        Forms\Components\Actions\Action::make('modalidade')->label('Modalidade')
                                                        ->badge(

                                                            function($record){
                                                                if($record){
                                                                    return EmbarqueContainerModalidadeResposta::total_itens($record->id)['total_itens'];
                                                                }
                                                                else
                                                                    return 0;
                                                            }
                                                        )
                                                        ->label('Modalidade')
                                                        ->button()
                                                        ->color('info')
                                                        ->url(
                                                            function($record){
                                                                if($record){
                                                                    return EmbarqueContainerChecklistModalidadeResource::getUrl('index', 
                                                                    [
                                                                        'embarques_containers_id' => $record->id, 
                                                                        'embarque_id' => $record->embarque_id,
                                                                    ]);
                                                                }
                                                                else
                                                                    return null;
                                                                }),                                                  

                                                    ]), 
                                                    Forms\Components\Actions::make([
                                                        Forms\Components\Actions\Action::make('lacre')->label('Lacração')
                                                        ->badge(

                                                            function($record){
                                                                if($record)
                                                                    return EmbarqueContainerLacracaoResposta::total_itens($record->id)['total_itens'];
                                                                else
                                                                    return 0;
                                                            }
                                                        )
                                                        ->url(
                                                            function($record){
                                                                if($record)
                                                                    return EmbarqueContainerLacracaoRespostaResource::getUrl('index', 
                                                                    [
                                                                        'embarques_containers_id' => $record->id, 
                                                                        'embarque_id' => $record->embarque_id,
                                                                        'lacracao_id' => $record->lacracao_id
                                                                    ]);
                                                                else
                                                                    return null;
                                                            })

                                                    ]),      
                                                    ])->columns(3)                                                                                                                                                     
                                                ]),
                                            Tabs\Tab::make('Usuários')
                                                ->schema([
                                                    Grid::make()
                                                    ->schema([
                                                        Forms\Components\Select::make('user_id_questionario')
                                                        ->options(\App\Models\User::all()->pluck('name', 'id'))
                                                        ->label('Usuário do Questionário'),

                                                        Forms\Components\Select::make('user_id_modalidade')
                                                        ->options(\App\Models\User::all()->pluck('name', 'id'))
                                                        ->label('Usuário da Modalidade'),
                                                        
                                                        Forms\Components\Select::make('user_id_lacres')
                                                        ->options(\App\Models\User::all()->pluck('name', 'id'))
                                                        ->label('Usuário do Lacre'),   
                                                    ])
                                                                                                   
                                                ])->visible(
                                                    function($record){
                                                        if($record){
                                                            $embarque = Embarque::find($record->embarque_id);
                                                          //  dd($embarque);
                                                            if($embarque)
                                                            //se embarque finalizado e usuario for superadmin
                                                                return auth()->user()->login == 'super.admin';
                                                            else
                                                                return false;
                                                        }
                                                        else
                                                            return false;
                                                    }
                                                ) ,

                                            ]),
                                        


                                    ])
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        if($data['user_id_questionario'] != null){
                                            EmbarqueContainerChecklistResposta::where('embarques_containers_id', $data['id'])
                                                ->update(['user_id' => $data['user_id_questionario']]);
                                        }
                                        
                                        if($data['user_id_modalidade'] != null){
                                            EmbarqueContainerModalidadeResposta::where('embarques_containers_id', $data['id'])
                                                ->update(['user_id' => $data['user_id_modalidade']]);
                                        }
                                        if($data['user_id_lacres'] != null){
                                            EmbarqueContainerLacracaoResposta::where('embarques_containers_id', $data['id'])
                                                ->update(['user_id' => $data['user_id_lacres']]);
                                        }

                                        return $data;
                                    })
                                    
                                    ->grid(2)
                                    ->live()
                                    ->addActionLabel('Adicionar Container')
                                    ->addable(
                                        function(?Embarque $record){
                                            //if($record)
                                            //    return $record->em_edicao;
                                            return true;
                                        }
                                    )
                                    ->collapsible()->collapsed()
                                    ->itemLabel(
                                        function($state){
                                            $label = '';
                                            if(isset($state['container'])){
                                                if(auth()->user()->login == 'super.admin')
                                                    $label = $state['container'] . ' - ' . $state['id'];
                                                else
                                                    $label = $state['container'];
                                            }

                                            return $label;
                                        }
                                    )   
                                ])->columns(1),                      
                            ])->columns(1),

  
                            

                ])->columns(1),




            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status_embarque')
                ->label('Status do Embarque')
                ->getStateUsing(
                    function($record){
                        switch ($record->status_embarque) {
                            case 'L':
                                return 'Liberado';
                                break;
                            case 'B':
                                return 'Bloqueado';
                                break;    
                            case 'F':
                                return 'Finalizado';
                                break;                                                              
                            
                            default:
                            return 'Bloqueado';
                                break;
                        }
                    }
                ),
                Tables\Columns\TextColumn::make('id')->label('Embarque')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),            
                    Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(
                        function($record){
                            if($record->tipo == 'N') 
                                return 'Embarque Diário';
                            else
                                return 'Adiantamento de Embarque';
                        }
                    )
                    ->color(function ($state) {
                        if($state == 'N') return 'success';
                        else return 'warning';
                    })
                    ->searchable(),                        
                Tables\Columns\TextColumn::make('clientes.nome')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transportadoras.nome')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('contrato')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('navio')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_sacas')->label('Sacas')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric(),
                Tables\Columns\TextColumn::make('t_containers')->label('Containers')
                    ->default(function(Embarque $record){
                        return Embarque::total_containers($record->id);
                    })
                    ->badge()
                    ->color(
                        function($record){
                            $total = Embarque::total_containers_reprovados($record->id);
                            if($total > 0)
                                return 'danger';
                            else
                                return 'success';
                        }
                    )
                    ->numeric(),                    
                Tables\Columns\TextColumn::make('data')
                    ->date('d/m/Y')
                    ->sortable(),                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                    /*
                Tables\Columns\TextColumn::make('arquivo_pdf')
                        ->label('PDF')
                    ->formatStateUsing(
                        function($record){
                            $file = url('storage/arquivos_pdf/'.$record->arquivo_pdf);
                            return new HtmlString('<a href="'.$file.'" target="_blank"> <img src=""> Baixar PDF </a>');
                        }),
                        
                        */

                //Tables\Columns\TextColumn::make('arquivo_pdf')
                //    ->sortable(),                     
                IconColumn::make('em_edicao')
                    ->label('Em Edição')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-mark')
                    ->falseIcon('heroicon-o-check-badge')
                    ->falseColor('success')
                    ->trueColor('danger'),                        
                    
                Tables\Columns\TextColumn::make('user_edicao.name')
                ->toggleable(isToggledHiddenByDefault: true),                                    
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options(['N' => 'Novo', 'A' => 'Adiantamento'])
                    ->label('Tipo de Embarque'),
                SelectFilter::make('status_embarque')
                    ->options(['L' => 'Liberado', 'B' => 'Bloqueado', 'F' => 'Finalizado'])
                    ->label('Status do Embarque')
                    ->default('L'),
                SelectFilter::make('cliente_id')
                    ->label('Cliente')
                    ->options(Cliente::all()->pluck('nome', 'id')),
                    SelectFilter::make('transportadora_id')
                    ->label('Transportadora')
                    ->options(Transportadora::all()->pluck('nome', 'id')),


                    
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                ActionGroup::make([

                    Tables\Actions\DeleteAction::make('excluir')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('Excluir')
                    ->visible(fn (Embarque $record) => $record->status_embarque != 'F'),

                    Tables\Actions\Action::make('liberar_admin')
                        ->requiresConfirmation()
                        ->modalHeading('Liberar checklist')
                        ->modalDescription('Serão geradas novas respostas para o checklist. Deseja continuar?')
                        ->modalSubmitActionLabel('Sim, estou ciente')
                        ->modalIcon('heroicon-o-information-circle')
                        ->color('success')
                        ->icon('heroicon-o-information-circle')
                        ->modalIconColor('success')
                        ->label(
                            function(Embarque $record){
                                return $record->status == 'L'? 'Refazer Estrutura do Checklist':'Liberar para Checklist';
                            }
                        )
                        ->visible(
                            function(Embarque $record){
                                return auth()->user()->login !='super.admin' && $record->status_embarque == 'B';
                            })
                            
                        ->action(                            
                            function (Embarque $record) {

                                if($record->tipo == 'A'){
                                    Notification::make()
                                    ->warning()
                                    ->title('Não é possível gerar Checklist')
                                    ->body('O Embarque ainda está em fase de Adiantamento')
                                    ->send();
                                }
                                else{
                                             
                                    $resp = Embarque::LiberarParaChecklist($record->id);

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
                                }
                    }),                                       
                    
                    Tables\Actions\Action::make('liberar_edicao')
                        ->requiresConfirmation()
                        ->icon(function(Embarque $record){
                            return $record->em_edicao == 1 ? 'heroicon-o-check-badge':'heroicon-o-x-mark';
                        })
                        ->visible(fn (Embarque $record) => $record->status_embarque != 'F')
                        ->label(function(Embarque $record){
                            return $record->em_edicao == 1 ? 'Liberar Bloqueio Edição':'Bloquear para Edição ';
                        })  
                        ->requiresConfirmation() 
                        ->action(
                            function (Embarque $record) {
                                if($record->em_edicao == 1) 
                                    $resp = Embarque::LiberarBloqueioEdicao($record);            
                                else
                                    $resp = Embarque::BloquearParaEdicao($record);
    
                                if($resp['status']== true){
                                    Notification::make()
                                    ->success()
                                    ->title('Sucesso')
                                    ->body($resp['mensagem'])
                                    ->send();
                                }
                                else{
                                    Notification::make()
                                    ->warning()
                                    ->title('Não foi possível')
                                    ->body('Motivo: ' . $resp['mensagem'])
                                    ->send();
                                }
                            }
                        ), 

                        Tables\Actions\Action::make('gerar_pdf')
                        ->icon('heroicon-o-magnifying-glass')
                        ->label('Gerar PDF')
                        ->action(
                            function(Embarque $record){
                                Embarque::GerarPdf($record->id);
                            }
                        )->visible(function ($record): bool {
                            return Embarque::PodeGerarPDFEmbarque($record); ;
                        }),

                        Tables\Actions\Action::make('baixar_pdf')
                        ->label('Baixar PDF')
                        ->icon('heroicon-o-document')
                      
                            ->url(
                                fn (Embarque $record): string => route('embarque.report', ['record' => $record]),
                                shouldOpenInNewTab: true
                            )->visible(function ($record): bool {
                                return Embarque::PodeGerarPDFEmbarque($record); ;
                            })
                        
                ])

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
            'index' => Pages\ListEmbarques::route('/'),
            'edit' => Pages\EditEmbarque::route('/{record}/edit'),
            'create' => Pages\CreateEmbarque::route('/create'),
        ];
    }


}
