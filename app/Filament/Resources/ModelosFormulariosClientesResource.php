<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ModeloFormulario;
use Filament\Resources\Resource;
use App\Models\ModeloRespostaTipo;
use App\Traits\TraitSomenteUsuario;
use Filament\Tables\Actions\Action;
use App\Models\ModeloRespostaPontuacao;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ModelosFormulariosClientes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ModelosFormulariosClientesResource\Pages;
use App\Filament\Resources\ModeloFormularioResource\Pages\FormModeloFormulario;
use App\Filament\Resources\ModelosFormulariosClientesResource\RelationManagers;

class ModelosFormulariosClientesResource extends Resource
{
    use TraitSomenteUsuario;
    
    protected static ?string $model = ModeloFormulario::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    //protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Modelos de Formulários';

    protected static ?string $pluralLabel = 'Modelos Disponíveis';

    protected static ?string $navigationGroup = 'Configurar Formulários';

    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                FormModeloFormulario::getComponents()
            );
    }

    public static function table(Table $table): Table
    {

        return $table
            ->modifyQueryUsing( function (Builder $query) {

                return $query->where('status', 1);
                
            })
            ->columns([
                Tables\Columns\TextColumn::make('segmento.nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label('Descrição do Formulário'),
                    IconColumn::make('status')
                    ->searchable()
                    ->label('Status'),                    
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i:m')
                ->label('Criado em'),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime('d/m/Y H:i:m')
                ->label('Últ. Atualização'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('verView')
                ->label('Visualizar')
                  
                ->action(function () {
                    // Aqui pode incluir lógica ou apenas redirecionar para a view
                })
                ->icon('heroicon-m-eye')
                ->color('info')
                ->modalWidth('80%')
                ->modalContent(
                    function (Model $record) {
                        $dados = ModeloFormulario::ObterDadosView($record->id);
                        return view('filament.modelo.formulario',
                            [
                                'dados' => $dados,
                            ]
                        );
                }),

                Action::make('liberar')
                    ->label('Pedir Modelo')
                    ->requiresConfirmation()
                    ->modalHeading('Efetuar Pedido')
                    ->modalDescription('Aceitando, você estará pedindo o pedido do modelo para uso')
                    ->modalSubmitActionLabel('Sim, desejo utilizar')
                    ->icon('heroicon-m-credit-card')
                    ->color('primary')
                                        
                    ->action(

                        function(ModeloFormulario $record){

                            $resp = ModeloFormulario::PedirModelo($record);
                            if($resp['status']== true){
                                Notification::make()
                                ->success()
                                ->title('Pedido realizado com Sucesso')
                                ->body($resp['mensagem'])
                                ->send();

                                
                            }
                            else{
                                Notification::make()
                                ->warning()
                                ->title('Pedido não realizado')
                                ->body('Motivo: ' . $resp['mensagem'])
                                ->send();
                            }
                        }
                    ) 
                    ->visible(

                        function(ModeloFormulario $record){

                            $ret = ModeloFormulario::StatusPedidoUtilizacao($record);

                            if($ret === null){
                                return true;
                            }

                            return false;                                
                        }

                    ),

                    Action::make('aguardar')
                    ->label('Aguardando Liberação')
                    ->color('warning')
                      
                    ->visible(
                        function(ModeloFormulario $record){

                            $ret = ModeloFormulario::StatusPedidoUtilizacao($record);

                            if($ret !== null){
                                if($ret['status'] == 1){
                                    return true;
                                }
                            }

                            return false;                                
                        }
                    ),   
                    
                    Action::make('liberado')
                    ->label('Utilizar Modelo')
                    ->requiresConfirmation()
                    ->modalHeading('Efetuar Utilização')
                    ->modalDescription("Aceitando, você estará liberando o modelo para uso. Isso irá gerar um formulário para ser Configurado conforme sua necessidade") 
                    ->modalSubmitActionLabel('Sim, desejo liberar')
                    ->icon('heroicon-m-x-mark')
                    ->color('success')
                                        
                    ->action(

                        function(ModeloFormulario $record){
                            $resp = ModeloFormulario::GerarFormulario($record);
                            
                            if($resp['status']== true){
                                Notification::make()
                                ->success()
                                ->title('Pedido realizado com Sucesso')
                                ->body($resp['mensagem'])
                                ->send();
                            }
                            else{
                                Notification::make()
                                ->warning()
                                ->title('Pedido não realizado')
                                ->body('Motivo: ' . $resp['mensagem'])
                                ->send();
                            }
                        }
                    ) 
                    ->visible(
                        function(ModeloFormulario $record){

                            $ret = ModeloFormulario::StatusPedidoUtilizacao($record);

                            if($ret !== null){
                                if($ret['status']== 3){
                                    return true;
                                }
                            }
                            return false;                                
                        }
                    ),

                    Action::make('aguardar')
                    ->label('Modelo já utilizado')
                    ->color('primary')
                      
                    ->visible(
                        function(ModeloFormulario $record){

                            $ret = ModeloFormulario::StatusPedidoUtilizacao($record);

                            if($ret !== null){
                                if($ret['status']== 4){
                                    return true;
                                }
                            }
                            return false;                                
                        }
                    ), 
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
            'index' => Pages\ListModelosFormulariosClientes::route('/'),
        ];
    }

}
