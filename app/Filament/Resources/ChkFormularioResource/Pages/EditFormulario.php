<?php
namespace App\Filament\Resources\ChkFormularioResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\FormularioResource;

class EditFormulario extends EditRecord
{
    protected static string $resource = FormularioResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = User::find(auth()->id());

        $data['user_id'] = $user->id;
        $data['empresa_id'] = $user->empresa_id;
        return $data;
    }


}
