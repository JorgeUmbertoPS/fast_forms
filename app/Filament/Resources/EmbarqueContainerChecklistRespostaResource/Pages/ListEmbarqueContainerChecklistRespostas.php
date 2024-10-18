<?php

namespace App\Filament\Resources\EmbarqueContainerChecklistRespostaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\EmbarqueContainerChecklistResposta;
use App\Filament\Resources\EmbarqueContainerChecklistRespostaResource;
use App\Models\EmbarqueContainer;

class ListEmbarqueContainerChecklistRespostas extends ListRecords
{
    protected static string $resource = EmbarqueContainerChecklistRespostaResource::class;

    protected static ?string $title = 'Fotos do Container ';

    public $embarque_id, $embarques_containers_id, $questionario_id;

    public function mount():void
    {
        if(isset($_REQUEST['embarque_id']) && isset($_REQUEST['embarques_containers_id']) && isset($_REQUEST['questionario_id'])){
            $this->embarque_id              = $_REQUEST['embarque_id'];
            $this->embarques_containers_id  = $_REQUEST['embarques_containers_id'];
            $this->questionario_id          = $_REQUEST['questionario_id'];

            self::$title = 'Fotos do Container - ' . EmbarqueContainer::where('id', $this->embarques_containers_id)->first()['container'];
        }
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
