<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Modalidade;
use Filament\Tables\Table;
use Illuminate\Routing\Route;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Models\EmbarqueContainerChecklistResposta;
use App\Models\EmbarqueContainerModalidadeResposta;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource\Pages;




class EmbarqueContainerChecklistModalidadeResource extends Resource
{
    protected static ?string $model = EmbarqueContainerModalidadeResposta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Alterar Foto da Modalidade';

    protected static $embarques_containers_id;
    protected static $embarque_id;

    //mount: embarques_containers_id, embarque_id
    //mount
    public static function mount($embarques_containers_id, $embarque_id){
        self::$embarques_containers_id = $embarques_containers_id;
        self::$embarque_id = $embarque_id;
        
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                ->schema([
                    FileUpload::make('imagem')->label('Foto')
                        ->image()
                        ->imageEditor(false)
                        ->previewable(true)
                        ->imageEditorEmptyFillColor('#FFFFFF')
                        ->storeFiles(true)
                        ->disk('public')
                        ->visibility('public')
                        ->deletable(true)
                        ->preserveFilenames(false)
                        ->imagePreviewHeight('600')
                        ->afterStateUpdated(
                            function ($record, TemporaryUploadedFile $state) {
                                EmbarqueContainerModalidadeResposta::SalvaImagem($state, $record);
                            }
                        )
                ])->columns(2)
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
                    function (EmbarqueContainerModalidadeResposta $record){
                        EmbarqueContainerModalidadeResposta::excluitChecklistModalidadeResposta($record->id);
                        redirect()->to('admin/embarque-container-checklist-modalidades?embarques_containers_id='.$record->embarques_containers_id.'&embarque_id='.$record->embarque_id);
                    })
                    ->visible(function (Model $record) {
                        return $record->pergunta_imagem != 'I';
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
       
        if(isset($_REQUEST['embarques_containers_id'])){
            return parent::getEloquentQuery()->where('embarque_id', request('embarque_id'))
                                            ->where('embarques_containers_id', request('embarques_containers_id'));
        }

        return parent::getEloquentQuery();


    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmbarqueContainerChecklistModalidades::route('/'),
            'create' => Pages\CreateEmbarqueContainerChecklistModalidade::route('/create'),
            'edit' => Pages\EditEmbarqueContainerChecklistModalidade::route('/{record}/edit'),
        ];
    }

    public static function getBreadcrumb(): string
    {
        return '';
    }
}
