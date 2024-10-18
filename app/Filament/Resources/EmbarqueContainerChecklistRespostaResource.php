<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Models\EmbarqueContainerChecklistResposta;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\EmbarqueContainerChecklistRespostaResource\Pages;
use App\Filament\Resources\EmbarqueContainerChecklistRespostaResource\RelationManagers;

class EmbarqueContainerChecklistRespostaResource extends Resource
{
    public static $embarques_containers_id;
    public static $embarque_id;

    protected static ?string $model = EmbarqueContainerChecklistResposta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Alterar Foto do Checklist';

    protected static bool $shouldRegisterNavigation = false;
    
    public function mount($embarques_containers_id, $embarque_id)
    {
        self::$embarques_containers_id = $embarques_containers_id;
        self::$embarque_id = $embarque_id;        
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                ->schema([
                    FileUpload::make('resposta')->label('Foto')
                        ->image()
                        ->imageEditor(false)
                        ->previewable(true)
                        ->imageEditorEmptyFillColor('#FFFFFF')
                        ->storeFiles(true)
                        ->disk('public')
                        ->visibility('public')
                        ->deletable(true)
                        ->imagePreviewHeight('600')
                        ->preserveFilenames(false)
                        ->afterStateUpdated(
                            function (EmbarqueContainerChecklistResposta $record, TemporaryUploadedFile $state) {
                                EmbarqueContainerChecklistResposta::SalvaImagem($state, $record);
                            }
                        )
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
        
            ->columns([
                ImageColumn::make('resposta')
                ->width(100)
                ->height(100)
                ->label('Foto')
                ->getStateUsing(
                    function(EmbarqueContainerChecklistResposta $record){
                        
                        if($record->pergunta_imagem == 'I')
                            return $record->resposta;
                        else
                            return null;
                    }
                )
                ,
                Tables\Columns\TextColumn::make('sequencia'),
                Tables\Columns\TextColumn::make('pergunta')
                    ->getStateUsing(
                        function(EmbarqueContainerChecklistResposta $record){
                            
                            if($record->pergunta_imagem == 'P')
                                return $record->pergunta .' - '. $record->texto;
                            else
                                //return $record->resposta;
                                return 'Foto';
                        }
                    )->wrap()
                    ->searchable(),
                    Tables\Columns\IconColumn::make('pergunta_imagem')->label('Tipo')
                    ->icon(
                        function(EmbarqueContainerChecklistResposta $record){
                            return $record->pergunta_imagem == 'P' ? 'heroicon-o-clipboard-document-list': 'heroicon-o-camera';
                        }
                    ),
                    Tables\Columns\IconColumn::make('pergunta_neutra')->label('Neutra'),
                    Tables\Columns\IconColumn::make('pergunta_finaliza_negativa')->label('Finaliza Negativa'),

            ])->recordUrl(fn () => null)
            ->filters([
                //
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\EditAction::make('alterar_foto')
                ->label('Alterar Foto')
                ->visible(function (Model $record) {
                    return $record->pergunta_imagem == 'I';
                }),
                Action::make('delete')
                    ->label('Excluir Pergunta')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                ->action(
                    function (EmbarqueContainerChecklistResposta $record){
                        $record->excluitChecklistResposta($record->id);
                        redirect()->to('admin/embarque-container-checklist-respostas?embarques_containers_id='.$record->embarques_containers_id.'&embarque_id='.$record->embarque_id);
                    })
                    ->visible(function (Model $record) {
                        return $record->pergunta_imagem != 'I';
                    })
            ])
            ->bulkActions([

            ]);
    }


    public static function getEloquentQuery(): Builder
    {

        
        if(isset($_REQUEST['embarques_containers_id'])){
            return parent::getEloquentQuery()->where('embarque_id', $_REQUEST['embarque_id'])
                                            ->where('embarques_containers_id', $_REQUEST['embarques_containers_id']);
        }
        
        return parent::getEloquentQuery();

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
            'index' => Pages\ListEmbarqueContainerChecklistRespostas::route('/'),
            'create' => Pages\CreateEmbarqueContainerChecklistResposta::route('/create'),
            'edit' => Pages\EditEmbarqueContainerChecklistResposta::route('/{record}/edit'),
        ];
    }

    public static function getBreadcrumb(): string
    {
        return '';
    }
}
