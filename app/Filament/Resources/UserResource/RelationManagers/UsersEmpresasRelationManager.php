<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserEmpresa;
use App\Models\Pba\EmpresaTipo;
use App\Models\Pba\EmpresaUsuario;
use Filament\Tables\Actions\Action;
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
                Select::make('empresa_id')
                    ->label('Empresa')
                    ->options(
                        Empresa::all()->pluck('nome', 'id')
                    )
                    ->required(),

                Select::make('role')
                    ->label('Perfil')
                    ->options([
                        'admin' => 'Administrador',
                        'user' => 'Usuário',
                    ])
                    ->required(),

                    TextInput::make('name')
                        ->autofocus()
                        ->required(),    
                        
                        Forms\Components\Hidden::make('password'),   

                TextInput::make('email')
                    ->label('Email')
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
                Tables\Columns\TextColumn::make('role')->label('Perfil'),
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
            $data['password'] = bcrypt($data['password']);  
     
            return $data;
        });

    }
}
