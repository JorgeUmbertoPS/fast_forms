<?php

namespace App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource\Pages;

use App\Filament\Resources\EmbarqueContainerLacracaoRespostaResource;
use App\Models\EmbarqueContainerLacracaoResposta;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmbarqueContainerLacracaoResposta extends CreateRecord
{
    protected static string $resource = EmbarqueContainerLacracaoRespostaResource::class;

    protected static ?string $title = 'Incluir Pergunta da Modalidade do Checklist';

    protected function fillForm(): void
    {
        parent::fillForm();

        $request = request();

        $this->form->fill($request->query());
    }

    public function mutateFormDataBeforeCreate(array $data): array{

        $dados = EmbarqueContainerLacracaoResposta::where('embarques_containers_id', $data['embarques_containers_id'])
                                        ->where('embarque_id', $data['embarque_id'])
                                        ->orderBy('sequencia', 'DESC')
                                        ->first();

        $data['status'] = 'A';
        $data['sequencia'] = $dados->sequencia + 1;

        return $data;
    }
}
