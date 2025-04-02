<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
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
use Filament\Notifications\Auth\CustomResetPassword;
use Filament\Resources\RelationManagers\RelationManager;

class UsersEmpresasRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Usuário Administrador';

    protected static ?string $icon = 'heroicon-o-user';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //Hidden::make('empresa_id'),
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
            ->recordTitleAttribute('Usuário')
            ->heading('Usuário Administrador')
            ->description('A Empresa terá um único usuário administrador. Os demais serão cadastrados como usuários comuns na instância da Empresa')
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
                        $token = app('auth.password.broker')->createToken($record);
                        $url = \Filament\Facades\Filament::getResetPasswordUrl($token, $record);

                        $notification = new \App\Notifications\CustomResetPassword($token, $url);
                        
                        $record->notify($notification);

                        Notification::make()
                        ->title('Usuário criado com sucesso')	
                        ->iconColor('success')
                        ->color('success') 
                        ->body('Usuário Cadastrado e E-mail enviado')
                        ->send();
                    }
                )
                ->label('Novo')
                ->visible(
                    function() {
                        $model = $this->getOwnerRecord();
                        return !Empresa::PossuiUsuarioAdmin($model->id);
                    }
                ),
            ])
            ->actions([
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
        
        $action->using(function (array $data): User {

            $user = DB::table('users')->insert($data);
            $user = User::where('email', $data['email'])->first();
            $user->assignRole('ClienteAdmin'); // Substitua pelo nome da role desejada
    
            return $user;
        });
        

    }


}
