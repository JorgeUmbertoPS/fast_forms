<?php
namespace App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource\Pages;




use App\Filament\Resources\EmbarqueContainerChecklistModalidadeResource;
use App\Models\EmbarqueContainerModalidadeResposta;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmbarqueContainerChecklistModalidade extends CreateRecord
{
    protected static string $resource = EmbarqueContainerChecklistModalidadeResource::class;

    protected static ?string $title = 'Incluir Pergunta da Modalidade do Checklist';

    protected function fillForm(): void
    {
        parent::fillForm();

        $request = request();

        $this->form->fill($request->query());
    }

    public function mutateFormDataBeforeCreate(array $data): array{

        $dados = EmbarqueContainerModalidadeResposta::where('embarques_containers_id', $data['embarques_containers_id'])
                                        ->where('embarque_id', $data['embarque_id'])
                                        ->orderBy('sequencia', 'DESC')
                                        ->first();

        $data['status'] = 'A';
        $data['sequencia'] = $dados->sequencia + 1;

        return $data;
    }

       
}
