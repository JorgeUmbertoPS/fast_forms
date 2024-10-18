<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Models\EmbarqueContainerLacracaoResposta;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource\Pages;
use App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource\RelationManagers;

class EmbarqueContainerLacracaoRespostaResource extends Resource
{
    protected static ?string $model = EmbarqueContainerLacracaoResposta::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Alterar Foto do Lacre';

    public static function form(Form $form): Form
    {
        return $form
        
            ->schema([
                Section::make('')
                ->schema([
                    Section::make('')
                    ->schema([
                        FileUpload::make('imagem')->label('Foto')
                            ->image()
                            ->imageEditor(false)
                            ->previewable(true)
                            ->openable()
                            ->imageEditorEmptyFillColor('#FFFFFF')
                            ->storeFiles(true)
                            ->disk('public')
                            ->visibility('public')
                            ->deletable(true)
                            ->preserveFilenames(false)
                            ->imagePreviewHeight('600')
                            ->afterStateUpdated(
                                function ($record, TemporaryUploadedFile $state) {
                                    EmbarqueContainerLacracaoResposta::SalvaImagem($state, $record);
                                }
                            )
                    ])->columns(2)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            
           // ->query($model::where('state', $this->state))
            ->columns([
                ImageColumn::make('imagem')
                ->width(100)
                ->height(100)
                ->label('Foto'),
                Tables\Columns\TextColumn::make('sequencia'),
                Tables\Columns\TextColumn::make('descricao'),
                Tables\Columns\IconColumn::make('pergunta_neutra')->label('Neutra'),
                Tables\Columns\IconColumn::make('pergunta_finaliza_negativa')->label('Finaliza Negativa'),

            ])->recordUrl(fn () => null)
            ->filters([
                //
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Alterar Foto'),
                Action::make('delete')
                ->label('Excluir Pergunta')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->action(
                    function (EmbarqueContainerLacracaoResposta $record){
                        EmbarqueContainerLacracaoResposta::excluitChecklistLacracaoResposta($record->id);
                        redirect()->to('admin/embarque-container-lacracao-respostas?embarques_containers_id='.$record->embarques_containers_id.'&embarque_id='.$record->embarque_id);
                    })
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

    public static function getEloquentQuery(): Builder
    {
 
        if(isset($_REQUEST['embarque_id'])){
            return parent::getEloquentQuery()->where('embarque_id', $_REQUEST['embarque_id'])
                                            ->where('embarques_containers_id', $_REQUEST['embarques_containers_id']);
        }

        return parent::getEloquentQuery();

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmbarqueContainerLacracaoRespostas::route('/'),
            'create' => Pages\CreateEmbarqueContainerLacracaoResposta::route('/create'),
            'edit' => Pages\EditEmbarqueContainerLacracaoResposta::route('/{record}/edit'),
        ];
    }


    public static function getBreadcrumb(): string
    {
        return '';
    }
}
