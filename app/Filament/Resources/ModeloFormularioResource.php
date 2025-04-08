<?php

namespace App\Filament\Resources;

use Closure;
use Form\Set;
use Filament\Forms;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Formulario;

use Filament\Tables\Table;
use App\Models\PermissaoModel;
use App\Models\ModeloFormulario;
use Filament\Resources\Resource;
use App\Models\ModeloRespostaTipo;
use Filament\Tables\Actions\Action;
use App\Traits\TraitSomenteSuperUser;
use Illuminate\Database\Query\Builder;
use App\Models\ModeloRespostaPontuacao;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ModeloFormularioResource\Pages;
use App\Filament\Resources\ModeloFormularioResource\Pages\FormModeloFormulario;

class ModeloFormularioResource extends Resource
{
    use TraitSomenteSuperUser;
    
    protected static ?string $model = ModeloFormulario::class;

    protected static ?string $label = 'Formulário';
    protected static ?string $pluralLabel = 'Formulários';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Config. Modelos';

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

            ->columns([
                Tables\Columns\TextColumn::make('segmento.nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->label('Descrição do Formulário'),
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
                Tables\Actions\EditAction::make()->label('Alterar')  ,
                
                Action::make('liberar')
                ->label(
                    function(ModeloFormulario $record){
                        if($record->status == 1) return 'Bloquear';
                        else return 'Liberar';
                    }
                )
                ->requiresConfirmation()
                ->icon('heroicon-m-x-mark')
                ->color(
                    function(ModeloFormulario $record){
                        if($record->status == 1) return 'confirma';
                        else return 'primary';
                    }
                )
                                    
                ->action(fn (ModeloFormulario $record) => $record->LiberarBloquear($record->id)),  

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
                        return view('filament.modelo.modelo',
                            [
                                'dados' => $dados,
                            ]
                        );
                    }),

            ])
            ->bulkActions([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModeloFormularios::route('/'),
            'create' => Pages\CreateModeloFormulario::route('/create'),
            'edit' => Pages\EditModeloFormulario::route('/{record}/edit'),
            'view' => Pages\ViewModelo::route('/{record}'),
        ];
    }


}
