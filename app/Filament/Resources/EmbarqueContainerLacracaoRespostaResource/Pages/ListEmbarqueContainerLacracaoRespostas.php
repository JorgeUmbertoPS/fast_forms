<?php

namespace App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource\Pages;

use Filament\Actions;
use App\Models\EmbarqueContainer;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource;

class ListEmbarqueContainerLacracaoRespostas extends ListRecords
{
    protected static string $resource = EmbarqueContainerLacracaoRespostaResource::class;

    protected static ?string $title = 'Fotos dos Lacres';

    public $embarque_id, $embarques_containers_id, $lacracao_id;

    public function mount():void
    {

        if(isset($_REQUEST['embarque_id']) && isset($_REQUEST['embarques_containers_id']) && isset($_REQUEST['lacracao_id'])){
            $this->embarque_id              = $_REQUEST['embarque_id'];
            $this->embarques_containers_id  = $_REQUEST['embarques_containers_id'];
            $this->lacracao_id          = $_REQUEST['lacracao_id'];

            self::$title = 'Fotos dos Lacres do Container - ' . EmbarqueContainer::where('id', $this->embarques_containers_id)->first()['container'];
        }
    }

    protected function getHeaderActions(): array
    {
     
        return [

        ];
      
    }
}
