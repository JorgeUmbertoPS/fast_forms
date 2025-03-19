<?php
namespace App\Filament\Resources\ChkFormularioResource\Pages;



use App\Models\User;
use Filament\Actions;
use App\Models\FormularioPergunta;
use App\Models\FormularioResposta;
use App\Models\ModeloPergunta;
use App\Models\ModeloResposta;
use App\Models\ModeloFormulario;
use App\Models\ModeloPerguntaBloco;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\FormularioResource;

class CreateFormulario extends CreateRecord
{
    protected static string $resource = FormularioResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::find(auth()->id());

        $data['user_id'] = $user->id;
        $data['empresa_id'] = $user->empresa_id;
        return $data;
    }    
    
}
