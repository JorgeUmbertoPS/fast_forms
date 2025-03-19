<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use App\Traits\TraitSomenteUsuario;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuestionarioResource\Pages;
use App\Filament\Resources\QuestionarioResource\RelationManagers;

class QuestionarioResource extends Resource
{
    protected static ?string $model = Questionario::class;

    protected static ?string $label = 'Questionário';
    protected static ?string $recordTitleAttribute = 'Questionário';
    protected static ?string $pluralLabel = 'Responder Questionários';
    protected static ?string $navigationGroup = 'Questionários';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?int $navigationSort = 3;
    use TraitSomenteUsuario;
    
    //protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('nome'),
                Tables\Columns\TextColumn::make('data_inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_termino')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(function (Questionario $record) {
                        return $record->status == 0 ? 'Finalizado' : 'Aberto';
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('resposnder')
                    ->icon('heroicon-o-sparkles')
                    ->label('Responder')
                    ->color('primary')
                    ->button()
                    ->url(
                        function(Questionario $record) {
                            return ('questionario/'. $record->id);
                        }
                    )
                    ->visible(fn (Questionario $record): bool => $record->status == 1),
                    Tables\Actions\Action::make('imprimir')
                    ->icon('heroicon-o-printer')
                    ->label('Imprimir')
                    ->color('gray')
                    ->button()
                    ->url(
                        fn (Questionario $record): string => route('questionario.report', ['record' => $record]),
                        shouldOpenInNewTab: true
                    ),
                    Tables\Actions\Action::make('finalizar')
                    ->icon('heroicon-o-hand-thumb-up')
                    ->label('Finalizar')
                    ->color('warning')
                    ->button()
                    ->action(
                        function(Questionario $record) {
                            $ret = Questionario::FinalizarQuestionario($record->id);
                            if($ret['return'] == true){
                                Notification::make()
                                ->title('Questionário finalizado com sucesso')
                                ->success()
                                ->send();
                            }
                            else{
                                Notification::make()
                                ->title('Erro ao finalizar questionário')
                                ->danger()
                                ->send();
                            }
                        }
                    )
                    ->visible(fn (Questionario $record): bool => $record->status == 1),
 
                                     
            ], position: ActionsPosition::BeforeColumns)
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
            'index' => Pages\ListQuestionarios::route('/'),
           // 'responder' => Pages\EditQuestionario::route('/{record}/edit'),

        ];
    }
}
