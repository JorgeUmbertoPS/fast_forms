<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserEmpresa;
use App\Models\Pba\EmpresaTipo;
use App\Models\Pba\EmpresaUsuario;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Components\FormComponentes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UsersEmpresasRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('empresa_id'),
                //Hidden::make('admin_cliente'),
                    TextInput::make('name')
                        ->autofocus()
                        ->required(),    
                        
                        Forms\Components\Hidden::make('password'),   

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Usuários')
            ->heading('Usuários')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Usuário'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->after(
                    function ($record) {
                        $user = $record;
                        $token = app('auth.password.broker')->createToken($user);
                        $notification = new \Filament\Notifications\Auth\ResetPassword($token);
                        $notification->url = \Filament\Facades\Filament::getResetPasswordUrl($token, $user);
                        $user->notify($notification);

                        Notification::make()
                        ->title('Usuário criado com sucesso')	
                        ->iconColor('success')
                        ->color('success') 
                        ->body('Usuário Cadastrado e E-mail enviado')
                        ->send();
                    }
                )
                ->label('Novo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginationPageOptions([3, 25, 50, 100])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function configureCreateAction(Tables\Actions\CreateAction $action): void
    {
        parent::configureCreateAction($action);
        $action->mutateFormDataUsing(function ($data) {
            $model = $this->getOwnerRecord();
            $data['empresa_id'] = $model->id;
            $data['password'] = bcrypt($data['password']);  

            $data['admin_cliente'] = 1;
            return $data;
        });

    }

    protected function configureEditAction(Tables\Actions\EditAction $action): void
    {
        parent::configureEditAction($action);
        $action->mutateFormDataUsing(function ($data) {
            unset($data['password']);
            $data['admin_cliente'] = 1;
           // dd($data);
            return $data;
        });
    }

}
