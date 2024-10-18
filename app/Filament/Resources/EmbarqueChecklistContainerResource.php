<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Embarque;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use App\Models\EmbarqueContainer;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;

use App\Models\EmbarqueContainerLacracaoResposta;
use App\Models\EmbarqueContainerChecklistResposta;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\EmbarqueContainerModalidadeResposta;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\EmbarqueChecklistContainerResource\Pages;
use App\Filament\Resources\EmbarqueChecklistContainerResource\RelationManagers;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class EmbarqueChecklistContainerResource extends Resource
{
    protected static ?string $model = EmbarqueContainer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Container';

    protected static bool $shouldRegisterNavigation = false;

    protected static $img_ratio = '9:16';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Questionarios:')            
            ->description('Essa é a etapa dos Questionários')
            ->schema([

                Repeater::make('questionario')->label('')
                ->relationship('questionarios')
                ->live()
                ->schema([
                    
                    Hidden::make('id'),
                    Hidden::make('resposta'),
                    Hidden::make('questionario_id'),
                    Hidden::make('embarques_containers_id'),
                    Hidden::make('embarque_id'),
                    Hidden::make('sequencia'),                  

                    Grid::make() // grid questionario
                    ->schema([ 
                        Placeholder::make('')
                        ->content(function (EmbarqueContainerChecklistResposta $record){
                            if($record->pergunta_neutra == 0)
                                return new HtmlString('<b style="color:red;">*</b>  <b>'.$record->sequencia .' - '.$record->pergunta.'</b>');
                            else
                                return new HtmlString('<b>'.$record->sequencia .' - '.$record->pergunta.'</b>');
                        })->columnSpanFull(),

                        Placeholder::make('')
                            ->content(function (EmbarqueContainerChecklistResposta $record){
                                return new HtmlString($record->texto);
                        })->visible(fn($record)=> $record->texto != '')
                        ->columnSpanFull(),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('sim')->label('')
                                ->action(
                                    function (EmbarqueContainerChecklistResposta $record ) {
                                            $ret = EmbarqueContainerChecklistResposta::Responder($record, 'Sim');
                                        
                                            if($ret['return'] == false){
                                                Notification::make()
                                                ->title('Inconsistencia')	
                                                ->iconColor('danger')
                                                ->color('danger') 
                                                ->body($ret['mensagem'])
                                                ->send();
                                            }
                                        }
                                )
                                ->color(
                                    function (EmbarqueContainerChecklistResposta $record){
                                        $resposta = EmbarqueContainerChecklistResposta::find($record->id);
                                        return $resposta->resposta == 'Sim' ? 'success':'gray';
                                    })
                                ->icon('heroicon-o-hand-thumb-up')
                        ])
                        ->reactive()
                        ->columns(1),
                            
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('nao')->label('')
                                ->action(
                                    function (EmbarqueContainerChecklistResposta $record ) {
                                        $ret = EmbarqueContainerChecklistResposta::Responder($record, 'Não');
                                        
                                        if($ret['return'] == false){
                                            Notification::make()
                                            ->title('Finalizado')
                                            ->iconColor('danger')
                                            ->color('danger') 
                                            ->body($ret['mensagem'])
                                            ->send();
                                        }
                                    }
                                )
                                ->color(
                                    function (EmbarqueContainerChecklistResposta $record){
                                        $resposta = EmbarqueContainerChecklistResposta::find($record->id);
                                        return $resposta->resposta == 'Não' ? 'danger':'gray';
                                    })
                                ->icon('heroicon-o-hand-thumb-down')
                        ])
                        ->reactive()
                        ->columns(1),
                    ]) 
                    ->reactive()
                    ->visible(
                        function(EmbarqueContainerChecklistResposta $record){
                            $resposta = EmbarqueContainerChecklistResposta::find($record->id);
                            return $resposta->pergunta_imagem === 'P' && $resposta->visivel === 'S';
                        }
                    )->columns(4),
                    // grid questionario

                    // Grid imagens do questionário
                    Grid::make()
                    ->schema([
                        Forms\Components\Actions::make([
                            Action::make('texto_help')
                            ->Icon('heroicon-m-question-mark-circle')
                            ->iconButton()
                            ->label("Ajuda")
                            ->form([
                                RichEditor::make('')
                                ->toolbarButtons([])
                                    ->default(function (EmbarqueContainerChecklistResposta $record){
                                        return $record::obterHelp($record);
                                })
                                ->columnSpanFull(),   
                            ])->modalSubmitAction(false)
                        ])->visible(
                            function(EmbarqueContainerChecklistResposta $record){
                                $resposta = EmbarqueContainerChecklistResposta::find($record->id);
                                return $resposta->pergunta_imagem === 'I' && $resposta->visivel === 'S';
                            }
                        )->label('Ajuda com a Foto'),

                        Placeholder::make('')
                        ->content(function (EmbarqueContainerChecklistResposta $record){
                            //return new HtmlString('<b>'.$record->sequencia .' - '.$record->pergunta .'</b>');
                            if($record->pergunta_neutra == 0)
                                return new HtmlString('<b style="color:red;">*</b>  <b>'.$record->sequencia .' - '.$record->pergunta.'</b>');
                            else
                                return new HtmlString('<b>'.$record->sequencia .' - '.$record->pergunta.'</b>');
                            
                        })->reactive()
                        
                        ->columnSpanFull(),  
                        FileUpload::make('resposta')->label('')
                        ->image()
                        ->imageEditor(false)
                        ->previewable(true)
                        ->imageEditorEmptyFillColor('#FFFFFF')
                        ->storeFiles(true)
                        ->disk('public')
                        ->visibility('public')
                        ->deletable(true)
                        ->preserveFilenames(false)
                        ->afterStateUpdated(
                            function (EmbarqueContainerChecklistResposta $record, TemporaryUploadedFile $state) {
                                EmbarqueContainerChecklistResposta::SalvaImagem($state, $record);
                            }
                        )->columnSpanFull(),
                    ])
                    ->reactive()
                    ->visible(
                        function(EmbarqueContainerChecklistResposta $record){
                           $resposta = EmbarqueContainerChecklistResposta::find($record->id);
                           return $resposta->pergunta_imagem === 'I' && $resposta->visivel === 'S';
                        }),
                    // Grid imagens do questionário

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('finalizar')
                        ->label('Tentar Finalizar Etapa')
                        ->button()
                        ->color('warning')
                        ->icon('heroicon-o-question-mark-circle')
                        ->action(
                            function (EmbarqueContainerChecklistResposta $record): void {
                                //TODO-Desativar a action enquanto aguarda
                            $ret = EmbarqueContainerChecklistResposta::TentarFinalizarEtapa($record);
                            if($ret['return'] == true){
                                Notification::make()
                                ->title('Finalizado')
                                ->iconColor('success')
                                ->color('success') 
                                ->body($ret['mensagem'])
                                ->send();
                            }
                            else{
                                Notification::make()
                                    ->title('Inconsistências')
                                    ->iconColor('danger')
                                    ->color('danger') 
                                    ->body($ret['mensagem'])
                                    ->send();
                            }
                        })
                        ->disabled(
                            function (EmbarqueContainerChecklistResposta $record): bool {
                                return EmbarqueContainerChecklistResposta::ChecklistFinalizado($record->embarque_id, $record->embarques_containers_id);
                            }
                        ) 
                        ->requiresConfirmation()

                    ])->visible(
                        function (EmbarqueContainerChecklistResposta $record): bool {
                            $ultima = EmbarqueContainerChecklistResposta::UltimaPergunta($record);
                            $status_cancelameno = EmbarqueContainerChecklistResposta::StatusDeCancelamento($record);

                            if($ultima == true && $status_cancelameno == false){
                                return true;
                            }
                            elseif($status_cancelameno == true)
                                    return false;
                            else                            
                                return false;
                        })
                    ->fullWidth()
                    ->columnSpanFull(),

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('reprovar_container')
                        ->label('Você deseja reprovar este container e não dar sequência ao processo de embarque?')   
                        ->button()
                        ->color('danger')
                        ->icon('heroicon-o-hand-thumb-down')
                        ->action(
                            function (EmbarqueContainerChecklistResposta $record): void {
                                //TODO-Desativar a action enquanto aguarda
                            $ret = EmbarqueContainerChecklistResposta::ReprovarContainer($record);
                            if($ret['return'] == true){
                                Notification::make()
                                ->title('Finalizado')
                                ->iconColor('success')
                                ->color('success') 
                                ->body($ret['mensagem'])
                                ->send();
                            }
                            else{
                                Notification::make()
                                    ->title('Inconsistências')
                                    ->iconColor('danger')
                                    ->color('danger') 
                                    ->body($ret['mensagem'])
                                    ->send();
                            }
                        })
                        ->disabled(
                            function (EmbarqueContainerChecklistResposta $record): bool {
                                return EmbarqueContainerChecklistResposta::ChecklistFinalizado($record->embarque_id, $record->embarques_containers_id);
                            }
                        ) 
                        ->requiresConfirmation()

                    ])->visible(
                        function (EmbarqueContainerChecklistResposta $record): bool {
                            $ultima = EmbarqueContainerChecklistResposta::UltimaPergunta($record);
                            $status_cancelameno = EmbarqueContainerChecklistResposta::StatusDeCancelamento($record);

                            if($ultima == true && $status_cancelameno == true){
                                return true;
                            }
                            elseif($status_cancelameno == false)
                                    return false;
                            else                            
                                return false;
                        })

                    ->fullWidth()
                    ->columnSpanFull()                    
                
                ])// repeater questionario
                ->orderColumn('sequencia') 
                ->addable(false)
                ->reorderable(false)
                ->deletable(false)
                ->visible(
                    function (EmbarqueContainer   $record){
                        $ret = EmbarqueContainerChecklistResposta::ValidarResponder($record);

                        if ($ret['return'] == false){
                            return false;
                        }
                        else{
                            $cheklist_finalizado = EmbarqueContainerChecklistResposta::ChecklistFinalizado($record->embarque_id, $record->id);

                            if($cheklist_finalizado == true){
                                return false;
                            }
                            else{
                                return true;
                            }
                        }
                        return false;
                    }
                    
                )
                ->columns(1),
                Placeholder::make('')
                ->content(
                    function (EmbarqueContainer $record){
                        if(EmbarqueContainerChecklistResposta::ChecklistFinalizado($record->embarque_id, $record->id) == true)
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:green;"> Etapa de Questionários Finalizada! </div>');
                        else
                        $ret = EmbarqueContainerChecklistResposta::ValidarResponder($record);
                        if ($ret['return'] == false){
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:red;"> '.$ret['mensagem'].'</div>');
                        }
                        else{
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:red;"> Etapa de Questionários Pendente! </div>');
                        }
                    }
                )
                ->columnSpanFull(),
                
            ]), 

            // Modalidades
            Section::make('Modalidades')
            ->description('Essa é a etapa das Modalidades')
            ->schema([
                Grid::make()
                ->schema([
                    Repeater::make('modalidade')->label('')
                    ->relationship('modalidades')
                    ->schema([
                        Placeholder::make('')
                            ->content(function (EmbarqueContainerModalidadeResposta $record){
                                //return new HtmlString('<b>'.$record->sequencia .' - '.$record->descricao.'</b>');
                                if($record->pergunta_neutra == 0)
                                    return new HtmlString('<b style="color:red;">*</b>  <b>'.$record->sequencia .' - '.$record->descricao.'</b>');
                                else
                                    return new HtmlString('<b>'.$record->sequencia .' - '.$record->descricao.'</b>');                                
                        })
                        ->columnSpanFull(),  

                        Forms\Components\Actions::make([
                            Action::make('texto_help')
                            ->Icon('heroicon-m-question-mark-circle')
                            ->iconButton()
                            ->label("Ajuda")
                            ->form([
                                RichEditor::make('')
                                ->toolbarButtons([])
                                    ->default(function (EmbarqueContainerModalidadeResposta $record){
                                        return $record::obterHelp($record);
                                })
                                ->columnSpanFull(),   
                            ])->modalSubmitAction(false)
                        ])->label('Ajuda com a Foto'),

                        FileUpload::make('imagem')->label('')
                        ->image()
                        ->imageEditor(false)
                        ->previewable(true)
                        ->imageEditorEmptyFillColor('#FFFFFF')
                        ->storeFiles(false)
                        ->disk('public')
                        ->deletable(true)
                        ->preserveFilenames(false)
                        ->afterStateUpdated(
                            function (EmbarqueContainerModalidadeResposta $record, TemporaryUploadedFile $state) {
                                EmbarqueContainerModalidadeResposta::SalvaImagem($state, $record);
                        }),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('finalizar')->label('Finalizar Etapa')
                            ->button()
                            ->color('warning')
                            ->action(
                                function (EmbarqueContainerModalidadeResposta $record): void {

                                $ret = EmbarqueContainerModalidadeResposta::TentarFinalizarEtapa($record);
                                if($ret['return'] == true){
                                    Notification::make()
                                    ->title('Finalizado')
                                    ->iconColor('success')
                                    ->color('success') 
                                    ->body($ret['mensagem'])
                                    ->send();
                                }
                                else{
                                    Notification::make()
                                        ->title('Inconsistências')
                                        ->iconColor('danger')
                                        ->color('danger') 
                                        ->body($ret['mensagem'])
                                        ->send();
                                }
                                    
                            })->requiresConfirmation()

                        ])->visible(
                            function (EmbarqueContainerModalidadeResposta $record): bool {
                                return EmbarqueContainerModalidadeResposta::UltimaPergunta($record);
                            }
                        )
                        ->fullWidth()->columnSpanFull()                        

                    ])
                    //->itemLabel(fn (array $state): ?string => $state['sequencia'] .' - '. $state['descricao'] ?? null)
                    ->addable(false)
                    ->reorderable(false)
                    ->orderColumn('sequencia')
                    ->addable(false)                    
                    ->deletable(false)
                    ->columnSpanFull(),
                ])->visible(
                    function ($record): bool {
                        $ret = EmbarqueContainerModalidadeResposta::ValidarSalvaImagem($record);

                        if ($ret['return'] == false){
                            return false;
                        }
                        else{

                            $cheklist_finalizado = EmbarqueContainerChecklistResposta::ChecklistFinalizado($record->embarque_id, $record->id);
                            $modalidade_finalizada = EmbarqueContainerModalidadeResposta::ChecklistModalidadeFinalizado($record->embarque_id, $record->id);
        
                            if($modalidade_finalizada == true){
                                return false;
                            }
                            else{
                                if($cheklist_finalizado == true && $modalidade_finalizada == false){
                                    return true;
                                }
                            }
                        }
                        return false;
    
                    }
                )->columnSpanFull() ,
                Placeholder::make('')
                ->content(
                    function (EmbarqueContainer $record){
                        if(EmbarqueContainerModalidadeResposta::ChecklistModalidadeFinalizado($record->embarque_id, $record->id) == true)
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:green;"> Etapa de Modalidades Finalizada! </div>');
                        else{
                            $ret = EmbarqueContainerModalidadeResposta::ValidarSalvaImagem($record);
                            if ($ret['return'] == false){
                                return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:red;"> '.$ret['mensagem'].'</div>');
                            }
                            else{
                                return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:red;"> Etapa de Modalidades Pendente! </div>');
                            }
                        }
                        
                    }
                )->columnSpanFull(),

            ]),

            Section::make('Lacres')
            ->description('Essa é a etapa dos Lacres')
            ->schema([
                Grid::make()
                ->schema([
                    Repeater::make('lacre_id')->label('')
                    ->relationship('lacres')
                    ->live()
                    ->schema([
                        Hidden::make('id'),
                        Placeholder::make('')
                        ->content(function (EmbarqueContainerLacracaoResposta $record){
                            return new HtmlString('<b>'.$record->sequencia .' - '.$record->descricao.'</b>');
                         })
                    ->columnSpanFull(), 
                        Forms\Components\Actions::make([
                            Action::make('texto_help')
                            ->Icon('heroicon-m-question-mark-circle')
                            ->iconButton()
                            ->label("Ajuda")
                            ->form([
                                RichEditor::make('')
                                ->toolbarButtons([])
                                    ->default(function (EmbarqueContainerLacracaoResposta $record){
                                        return $record::obterHelp($record);
                                })
                                ->columnSpanFull(),   
                            ])->modalSubmitAction(false)
                        ])->label('Ajuda com a Foto'),
                        FileUpload::make('imagem')->label('')
                        ->image()
                        ->imageEditor(false)
                        ->previewable(true)
                        ->imageEditorEmptyFillColor('#FFFFFF')
                        ->storeFiles(false)
                        ->disk('public')
                        ->deletable(true)
                        ->preserveFilenames(false)
                        ->afterStateUpdated(
                            function (EmbarqueContainerLacracaoResposta $record, TemporaryUploadedFile $state) {
                                EmbarqueContainerLacracaoResposta::SalvaImagem($state, $record);
                            }
                        ),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('finalizar')->label('Finalizar Etapa')
                            ->button()
                            ->color('warning')
                            ->action(
                                function (EmbarqueContainerLacracaoResposta $record): void {
    
                                $ret = EmbarqueContainerLacracaoResposta::TentarFinalizarEtapa($record);
                                if($ret['return'] == true){
                                    Notification::make()
                                    ->title('Finalizado')
                                    ->iconColor('success')
                                    ->color('success') 
                                    ->body($ret['mensagem'])
                                    ->send();
                                }
                                else{
                                    Notification::make()
                                        ->title('Inconsistências')
                                        ->iconColor('danger')
                                        ->color('danger') 
                                        ->body($ret['mensagem'])
                                        ->send();
                                }
                                    
                            })->disabled(
                                function (EmbarqueContainerLacracaoResposta  $record){
                                    return $record::ChecklistLacreFinalizado($record->embarque_id, $record->embarques_containers_id) > 0;
                                })
                                    
                            ->requiresConfirmation()
    
                        ])->visible(
                            function (EmbarqueContainerLacracaoResposta $record): bool {
    
                                return EmbarqueContainerLacracaoResposta::UltimaPergunta($record);
                            }
                        )->fullWidth()
                        ->columnSpanFull()  
                    ])
                // ->itemLabel(fn (array $state): ?string => $state['sequencia'] .' - '. $state['descricao'] ?? null)
                    ->reorderable(false)
                    ->orderColumn('sequencia')
                    ->addable(false)
                    ->deletable(false)
                    ->columnSpanFull()
                ])->visible(
                    function ($record): bool {
                        $ret = EmbarqueContainerLacracaoResposta::ValidarSalvaImagem($record);

                        if ($ret['return'] == false){
                            return false;
                        }
                        else{
                            $cheklist_finalizado = EmbarqueContainerChecklistResposta::ChecklistFinalizado($record->embarque_id, $record->id);
                            $modalidade_finalizada = EmbarqueContainerModalidadeResposta::ChecklistModalidadeFinalizado($record->embarque_id, $record->id);
                            $lacres_finalizados = EmbarqueContainerLacracaoResposta::ChecklistLacreFinalizado($record->embarque_id, $record->id);
    
                            if($lacres_finalizados == true){
                                return false;
                            }
                            else{
                                if($cheklist_finalizado == true && $lacres_finalizados == false && $modalidade_finalizada == true){
                                    return true;
                                }
        
                            }
    
                        }
                        return false;
    
                    }
                )->columnSpanFull(),    
                Placeholder::make('')
                ->content(
                    function (EmbarqueContainer $record){
                        if(EmbarqueContainerLacracaoResposta::ChecklistLacreFinalizado($record->embarque_id, $record->id) == true)
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:green;"> Etapa de Lacres Finalizada! </div>');
                        else
                        $ret = EmbarqueContainerLacracaoResposta::ValidarSalvaImagem($record);
                        if ($ret['return'] == false){
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:red;">'.$ret['mensagem'].' </div>');
                        }
                        else{
                            return new HtmlString( '<div style="text-align:center; font-size:1em; font-weight:bold; color:red;"> Etapa de Lacres Pendente! </div>');
                        }
                    }
                )->columnSpanFull(),  
                      
            ]),

        ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmbarqueChecklistContainers::route('/'),
            'create' => Pages\CreateEmbarqueChecklistContainer::route('/create'),
            'edit' => Pages\EditEmbarqueChecklistContainer::route('/{record}/edit'),
        ];
    }
}
