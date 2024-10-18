<?php

namespace App\Filament\Resources;

use MyRepeater;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Modalidade;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Components\MyRepeaterComponent;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ModalidadeResource\Pages;
use App\Filament\Resources\ModalidadeResource\RelationManagers;

class ModalidadeResource extends Resource
{
    protected static ?string $model = Modalidade::class;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nome')->required()->maxLength(100),
                        Forms\Components\Textarea::make('texto'),  
                    ]),
                    Forms\Components\Section::make('Roteiros')  
                    ->icon('heroicon-m-check-circle')
                    ->description("Aqui serão cadastrados os Roteiros ou Sequencias de Fotos")      
                    ->schema([
                        MyRepeaterComponent::make('roteiros')
                        ->label('')
                        ->relationship('roteiros')
                        ->schema([
                            Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('descricao')->label('Descrição')->required(),
                                Forms\Components\TextInput::make('texto')->label('Texto'),
                                Forms\Components\Checkbox::make('pergunta_neutra')
                                ->label('Pergunta Neutra')
                                ->default(0)
                                ->inline(false)
                                ->columnSpan(1),
                                Checkbox::make('pergunta_finaliza_negativa')
                                ->label('Pergunta Finaliza Negativa')
                                ->default(0)
                                ->inline(false)
                                ->columnSpan(1),                                
                            ]),
                            Section::make('Dados para Ajuda') 
                            ->icon('heroicon-m-question-mark-circle')
                            ->description('Aqui são os dados usados para instruir os usuários')
                            ->schema([
                                Forms\Components\RichEditor::make('texto_help')
                                      ->label('Texto para ajudar a realizar as fotos')
                                    ->columnSpanFull(),                           
                                         
                            ])->columns(3)->collapsed()                             
                        ])
                        ->reorderable(true)
                        ->reorderableWithButtons()
                        ->reorderableWithDragAndDrop(true)
                        ->orderColumn('sequencia')                        
                        ->columns(1)
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(

                            function ($state) {
                                //dd($state);
                                //se o state estiver nulo, retorna null
                                if(!isset($state['sequencia'])){
                                    return null;
                                }
                                else
                                    return $state['sequencia'] .' - '. $state['descricao'] ;
                            })
                            
                        ->addActionLabel('Adicionar Roteiro')
                    ])
                ])
                
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('texto')
                    ->searchable(), 
                   
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModalidades::route('/'),
            'create' => Pages\CreateModalidade::route('/create'),
            'edit' => Pages\EditModalidade::route('/{record}/edit'),
        ];
    }
}
