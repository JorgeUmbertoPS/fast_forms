<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Embarque;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Questionario;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use App\Filament\Pages\CheckListPage;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use App\Models\EmbarqueContainerChecklistResposta;
use App\Filament\Resources\EmbarqueUsersResource\Pages;
use App\Filament\Resources\EmbarqueContainerChecklistRespostaResource;
use App\Filament\Resources\EmbarqueUsersResource\Pages\EmbarqueListContainers;
use App\Filament\Resources\EmbarqueUsersResource\Pages\EmbarqueChecklistContainer;

use function Safe\strftime;

class EmbarqueUsersResource extends Resource
{
    protected static ?string $model = Embarque::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralLabel = "Iniciar Embarque";

    protected static bool $shouldRegisterNavigation = false;


    public static function table(Table $table): Table
    {
        return $table
        ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')
                    ->visible(function(){
                        return auth()->user()->login == 'super.admin';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                    Tables\Columns\TextColumn::make('clientes.nome')
                    ->label('Cliente')
                    ->wrap(),
                Tables\Columns\TextColumn::make('transportadoras.nome')
                    ->label('Transportadora')
                    ->wrap(),                    
                Tables\Columns\TextColumn::make('contrato')
                    ->label('Contrato')
                    ->wrap(),
                           
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('data_de')->date('Y-m-d')->default(now()),
                    DatePicker::make('data_ate')->date('Y-m-d')->default(now()),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['data_de'],
                            fn (Builder $query, $date): Builder => $query->whereDate('data', '>=', $date),
                        )
                        ->when(
                            $data['data_ate'],
                            fn (Builder $query, $date): Builder => $query->whereDate('data', '<=', $date),
                        );
                })
            ])
            ->actions([
                Tables\Actions\Action::make('liberar')
                    ->icon('heroicon-o-truck')
                    ->label(
                        function(Embarque $record){
                            return $record->em_edicao === 1 ? 'Bloqueado para Edição':'Iniciar';
                        }
                    )
                    ->button()
                    ->color(
                        function(Embarque $record){
                            return $record->em_edicao === 1 ? 'warning':'success';
                        }
                    )
                    ->disabled(
                        function(Embarque $record){
                            return $record->em_edicao;
                        }
                    )
                    ->url(fn(Embarque $record): string =>  self::getUrl('containers', ['embarque_id' => $record])),                
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([

        ])->emptyStateHeading('Sem Embarques Liberados');;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = Embarque::query();
        $query->where('tipo', 'N')->where('status_embarque', 'L');
        return $query;
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
            'index' => Pages\ListEmbarqueUsers::route('/'),
            'containers' => EmbarqueListContainers::route('/containers/{embarque_id}/'),
           // 'container' => EmbarqueChecklistContainer::route('container/{container_id}/'),
        ];
    }
}
