<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use App\Models\PermissaoModel;
use App\Models\ModeloRelatorio;
use Filament\Resources\Resource;
use App\Traits\TraitSomenteUsuario;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuestionarioConfigResource\Pages;
use App\Filament\Resources\QuestionarioConfigResource\RelationManagers;
use App\Filament\Resources\QuestionarioConfigResource\RelationManagers\PlanoAcoesRelationManager;

class QuestionarioConfigResource extends Resource
{
    use TraitSomenteUsuario;
    
    protected static ?string $model = Questionario::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $label = 'Configurar Questionário';
    protected static ?string $pluralLabel = 'Configurar Questionários';
    protected static ?string $navigationGroup = 'Questionários';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return PermissaoModel::hasPermission('manipular-configurar_questionarios');
    }   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome ')
                            ->disabled(),
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título do Questionário')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('modelo_relatorio_id')
                            ->label('Modelo de Relatório')
                            ->options(ModeloRelatorio::all()->pluck('titulo', 'id'))
                            ->label('Modelo de Relatório')
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(3),

                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('avisar_dias_antes')->numeric(),
                        Forms\Components\DatePicker::make('data_inicio'),
                        Forms\Components\DatePicker::make('data_termino'),                        
                    ])
                    ->columns(3),                    

                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Checkbox::make('criar_resumo'),
                        Forms\Components\Checkbox::make('envia_email_etapas'),
                        Forms\Components\Checkbox::make('obriga_assinatura'),                                
                    ])
                    ->columns(3),
                    

                ])->columns(2)
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome do Questionário'),
                Tables\Columns\TextColumn::make('data_inicio')->dateTime('d/m/Y')->label('Data de Início'),
                Tables\Columns\TextColumn::make('data_termino')->dateTime('d/m/Y')->label('Data de Término'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Configurar'),
                Action::make('Atualizar')
                ->label('Atualizar')
                ->requiresConfirmation()
                ->icon('heroicon-m-arrow-path')
                ->color('success') 
                ->modalHeading('Atualizar Questionário')
                ->modalDescription('Aceitando, você atualizará o questionário com novas informações')
                ->modalSubmitActionLabel('Sim, desejo atualizar')
                ->action(
                    function(Questionario $record){
                        $ret = $record->AtualizarQuestionario($record->id);

                        dd($ret);
                    }),

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
            'index' => Pages\ListQuestionarioConfigs::route('/'),
            'create' => Pages\CreateQuestionarioConfig::route('/create'),
            'edit' => Pages\EditQuestionarioConfig::route('/{record}/edit'),
        ];
    }
}
