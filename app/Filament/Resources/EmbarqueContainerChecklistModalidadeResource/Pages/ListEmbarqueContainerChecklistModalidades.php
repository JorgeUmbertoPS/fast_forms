<?php
namespace App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource\Pages;

use Filament\Actions;
use App\Models\EmbarqueContainer;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource;

class ListEmbarqueContainerChecklistModalidades extends ListRecords
{
    protected static string $resource = EmbarqueContainerChecklistModalidadeResource::class;

    protected static ?string $title = 'Fotos das Modalidades do Checklist';

    public $embarque_id, $embarques_containers_id, $questionario_id;

    public function mount():void
    {

        if(isset($_REQUEST['embarque_id']) && isset($_REQUEST['embarques_containers_id'])){
            $this->embarque_id              = $_REQUEST['embarque_id'];
            $this->embarques_containers_id  = $_REQUEST['embarques_containers_id'];

            self::$title = 'Fotos das Modalidades do Checklist - ' . EmbarqueContainer::where('id', $this->embarques_containers_id)->first()['container'];
        }
    }

    protected function getHeaderActions(): array
    {
     
        return [
           

        ];
      
    }
}
