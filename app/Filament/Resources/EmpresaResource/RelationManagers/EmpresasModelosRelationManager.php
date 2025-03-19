<?php

namespace App\Filament\Resources\EmpresaResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ModeloPergunta;
use Filament\Tables\Actions\Action;
use App\Models\ModeloFormularioEmpresa;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class EmpresasModelosRelationManager extends RelationManager
{
    protected static string $relationship = 'empresas_modelos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('modelo_id')
                    ->label('Modelo')
                    ->options(ModeloPergunta::query()->pluck('nome', 'id')->where('status', 1))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Modelos')
            ->columns([
                Tables\Columns\TextColumn::make('modelo.nome')->label('Modelo'),
                Tables\Columns\IconColumn::make('status')
                ->boolean()
                ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([

                // action liberar
                Action::make('liberar')
                ->label('Liberar Modelo')
                ->requiresConfirmation()
                ->modalHeading('Efetuar Liberação')
                ->modalDescription("Liberando, o modelo será disponibilizado para a empresa.") 
                ->modalSubmitActionLabel('Sim, desejo liberar')
                ->icon('heroicon-m-banknotes')
                ->color('success')                  
                ->action(

                    function(ModeloFormularioEmpresa $record){

                        $resp = ModeloFormularioEmpresa::LiberarParaEmpresa($record);
                        
                        if($resp['status']== true){
                            Notification::make()
                            ->success()
                            ->title('Pedido liberado com Sucesso')
                            ->body($resp['mensagem'])
                            ->send();
                        }
                        else{
                            Notification::make()
                            ->warning()
                            ->title('Pedido não liberado')
                            ->body('Motivo: ' . $resp['mensagem'])
                            ->send();
                        }
                    }
                )->visible(fn ($record) => $record->status == 1)
                ,
                
                // action liberar
                Action::make('cancelar')
                ->label('Cancelar Modelo')
                ->requiresConfirmation()
                ->modalHeading('Efetuar Cancelamento')
                ->modalDescription("Cancelando, o modelo será cancelado para a empresa.") 
                ->modalSubmitActionLabel('Sim, desejo cacenlar')
                ->icon('heroicon-o-hand-thumb-down')
                ->color('warning')                  
                ->action(

                    function(ModeloFormularioEmpresa $record){

                        $resp = ModeloFormularioEmpresa::CancelarParaEmpresa($record);
                        
                        if($resp['status']== true){
                            Notification::make()
                            ->success()
                            ->title('Pedido cancelado com Sucesso')
                            ->body($resp['mensagem'])
                            ->send();
                        }
                        else{
                            Notification::make()
                            ->warning()
                            ->title('Pedido não cancelado')
                            ->body('Motivo: ' . $resp['mensagem'])
                            ->send();
                        }
                    }
                )->visible(fn ($record) => $record->status == 1),

                Action::make('liberado')
                ->label('Modelo já liberado')
                ->color('primary')                  
                ->visible(fn ($record) => $record->status == 3),
                
                Action::make('cancelado')
                ->label('Modelo não liberado')
                ->color('danger')                  
                ->visible(fn ($record) => $record->status == 4),                
            ])
            ->bulkActions([
                //,
            ]);
    }
}
