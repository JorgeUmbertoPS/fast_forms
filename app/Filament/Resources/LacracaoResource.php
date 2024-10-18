<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Lacracao;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Doctrine\DBAL\Schema\Schema;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Components\MyRepeaterComponent;
use App\Filament\Resources\LacracaoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LacracaoResource\RelationManagers;

class LacracaoResource extends Resource
{
    protected static ?string $model = Lacracao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Lacração';

    protected static ?string $pluralLabel = 'Lacrações';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
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
                    MyRepeaterComponent::make('perguntas')->label('')
                    ->relationship('lacracoes_roteiros')
                    ->schema([ 
                        Section::make('Dados para Checklist') 
                        ->schema([

                            Forms\Components\TextInput::make('descricao')
                                ->required()
                                ->columnSpan(3)
                                ->maxLength(255),
                            Forms\Components\TextInput::make('texto')
                                ->required()
                                ->columnSpan(3)
                                ->maxLength(255),   
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
                        ])->columns(3)  ,
                        Section::make('Dados para Ajuda') 
                        ->description('Aqui são os dados usados para instruir os usuários')
                        ->icon('heroicon-m-question-mark-circle')
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
                    ->collapsed()
                    ->collapsible()
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
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
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
            'index' => Pages\ListLacracaos::route('/'),
            'create' => Pages\CreateLacracao::route('/create'),
            'edit' => Pages\EditLacracao::route('/{record}/edit'),
        ];
    }
}
