<?php

namespace App\Filament\Resources\EmbarqueContainerChecklistRespostaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\EmbarqueContainerChecklistResposta;
use App\Filament\Resources\EmbarqueContainerChecklistRespostaResource;
use App\Filament\Resources\EmbarqueResource;

class CreateEmbarqueContainerChecklistResposta extends CreateRecord
{
    protected static string $resource = EmbarqueContainerChecklistRespostaResource::class;

    protected function fillForm(): void
    {
        parent::fillForm();

        $request = request();

        $this->form->fill($request->query());
    }

    public function mutateFormDataBeforeCreate(array $data): array{

        $dados = EmbarqueContainerChecklistResposta::where('embarques_containers_id', $data['embarques_containers_id'])
                                        ->where('questionario_id', $data['questionario_id'])
                                        ->where('embarque_id', $data['embarque_id'])
                                        ->orderBy('sequencia', 'DESC')
                                        ->first();

        $data['status'] = 'A';
        $data['sequencia'] = $dados->sequencia + 1;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return EmbarqueResource::getUrl('index');
    }
}


