<?php

namespace App\Filament\Resources\EmbarqueUsersResource\Pages;

use Pages;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmbarqueContainer;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Models\EmbarqueContainerModalidade;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Resources\EmbarqueUsersResource;
use App\Models\EmbarqueContainerLacracaoResposta;
use App\Models\EmbarqueContainerChecklistResposta;
use App\Models\EmbarqueContainerModalidadeResposta;
use App\Filament\Resources\EmbarqueChecklistContainerResource;

class EmbarqueListContainers extends Page implements HasTable
{
    use InteractsWithTable;
 
    public ?array $data = []; 

    private static $total_itens_chk = 0;
    private static $total_itens_abertos = 0;

    protected static string $resource = EmbarqueUsersResource::class;

    protected static ?string $title = 'Escolha um Container';

    protected static string $view = 'filament.resources.embarque-users-resource.pages.embarque-containers';

    private static $embarque_id;

    public function mount($embarque_id): void
    {
       self::$embarque_id = $embarque_id;
    }

    public static function table(Table $table): Table
    {

        return $table
            ->query(EmbarqueContainer::query()->where('embarque_id', self::$embarque_id))
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')
                    ->visible(function(){
                        return auth()->user()->login == 'super.admin';
                    }),
                Tables\Columns\TextColumn::make('container')
                ->label('Container'),                                                
                
                Tables\Columns\TextColumn::make('Status')
                    ->label('Status')
                    ->default(function(EmbarqueContainer $record){
                        $msg = '';
                        if ($record->finalizado == 1) $msg = 'Finalizado e ';
                        if ($record->finalizado == 0) $msg = 'Aberto e ';

                        if($record->status == 0) $msg .= 'Em avaliação';
                        if($record->status == 1) $msg .= 'Aprovado';
                        if($record->status == 2) $msg .= 'Reprovado';
                        
                        return $msg;
                    })
                    ->icon(
                        function(EmbarqueContainer $record){

                            if ($record->finalizado == 1 && $record->status == 1) //'Finalizado e Aprovado';
                                return 'heroicon-m-check-circle';
                                
                            if ($record->finalizado == 1 && $record->status == 2) //'Finalizado e Reprovado';
                                return 'heroicon-m-x-circle';

                            if ($record->finalizado == 0 && $record->status == 0) //'Aberto e Em avaliação';
                                return 'heroicon-m-clock';

                            if($record->finalizado == 0 && $record->status == 2) //'Aberto e Reprovado';
                                return 'heroicon-m-x-circle';
                        })
                    ->color(
                        function(EmbarqueContainer $record){
                            //dd($record->status, $record->finalizado);
                            if ($record->finalizado == 1 && $record->status == 1) //'Finalizado e Aprovado';
                                return 'success';
                                
                            if ($record->finalizado == 1 && $record->status == 2) //'Finalizado e Reprovado';
                                return 'danger';

                            if ($record->finalizado == 0 && $record->status == 0) //'Aberto e Em avaliação';
                                return 'info';

                            if ($record->finalizado == 0 && $record->status == 2) //'Aberto e Em avaliação';
                                return 'danger';                                

                        })
                    
                                          

            ])
            ->actions([
                Tables\Actions\Action::make('liberar')
                ->icon('heroicon-o-truck')
                ->label(
                    function(EmbarqueContainer $record){
                        return $record->finalizado == 1 ? 'Finalizado' : 'Iniciar';
                    }
                )
                ->disabled(
                    function(EmbarqueContainer $record){
                        return ($record->finalizado == 1 || $record->status == 2);
                    }
                )
                ->color(
                    function(EmbarqueContainer $record){
                        if($record->finalizado == 1 && $record->status != 2) return 'warning';
                        if($record->finalizado == 0 && $record->status != 2) return 'success';
                        if($record->status == 2) return 'danger';
                    }
                )
                ->button()
                ->url(
                    fn(EmbarqueContainer $record): string => EmbarqueChecklistContainerResource::getUrl('edit', ['record' => $record->id]))                
            ], position: ActionsPosition::BeforeColumns);

    }




}
