<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use App\Models\PermissaoModel;
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

    public static function shouldRegisterNavigation(): bool
    {
        return PermissaoModel::hasPermission('manipular-responder_questionarios');
    }  

    use TraitSomenteUsuario;
    
    

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
                // FILTRO DE STATUS MARCADO COMO FINALIZADO
                Tables\Filters\Filter::make('status')
                    ->label('Status')
                    ->query(fn (Builder $query): Builder => $query->where('status', 1))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\Action::make('resposnder')
                    ->icon('heroicon-o-sparkles')
                    ->label('Responder')
                    ->color('primary')
                    
                    ->url(
                        function(Questionario $record) {
                            return ('questionario/'. $record->id);
                        }
                    ),
                    //->visible(fn (Questionario $record): bool => $record->status == 1),
                
                Tables\Actions\Action::make('imprimir')
                    ->icon('heroicon-o-printer')
                    ->label('Imprimir')
                    ->color('gray')
                    
                    ->url(
                        fn (Questionario $record): string => route('questionario.report', ['record' => $record]),
                        shouldOpenInNewTab: true
                    ),

                Tables\Actions\Action::make('finalizar')
                    ->icon('heroicon-o-hand-thumb-up')
                    ->label('Finalizar')
                    ->color('warning')
                    
                    ->url(
                        fn (Questionario $record): string => route('filament.admin.resources.questionario-finalizas.edit', ['record' => $record]),
                        shouldOpenInNewTab: false
                    ), //admin/questionario-finalizas/{record}/edit ....... filament.admin.resources.questionario-finalizas.edit
 
                                     
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
