<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Perfil;
use App\Models\Empresa;
use Nette\Utils\Random;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PerfilModel;
use App\Traits\TraitOnlyTeam;
use App\Models\PermissaoModel;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Spatie\Permission\Contracts\Role;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\UsersEmpresasRelationManager;

class UserResource extends Resource
{
    use TraitOnlyTeam;
    
    protected static ?string $model = User::class;
    //protected static bool $shouldRegisterNavigation = false;
    protected static ?string $label = 'Usuário';

    protected static ?string $pluralLabel = 'Usuários';

    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function shouldRegisterNavigation(): bool
    {
        return PermissaoModel::hasPermission(PermissaoModel::PERMISSAO_USUARIO);
    }  

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('name')
                        ->autofocus()
                        ->required(),
                    TextInput::make('email')
                        ->required()
                        ->unique(ignoreRecord: true),


                    Select::make('empresa_id')
                        ->options(Empresa::pluck('nome','id'))
                        ->required()
                        ->label('Empresa')
                        ->visible(
                            function($record){
                                if(auth()->user()->SuperAdmin()){
                                    return true;
                                }
                                return false;
                            }
                        ),

                    Select::make('perfil_id')
                    ->options(PerfilModel::where('perfil_cliente', PerfilModel::PerfilCliente())->pluck('nome','id'))
                    ->required()
                    ->label('Perfil')                     

                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('roles_users.name')
                    ->label('Perfis')
                    ->searchable(),
                TextColumn::make('empresa.nome')
                    ->label('Empresa')
                    ->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('reenviar_senha')
                ->icon('heroicon-o-lock-closed')
                ->label('Reenviar Senha')
                ->action(
                    function ($record) {
                        User::SendMail($record);
                    }
                )
                ->visible(
                    function($record){
                       return $record->id != auth()->user()->id;
                    }
                )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
  
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(isset(auth()->user()->id))
            $user = User::find(auth()->user()->id);
        else
            $user = new User();
                
        if(!auth()->user()->SuperAdmin()){
            return parent::getEloquentQuery()
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->where('name', '<>', 'SuperAdmin');
        }
        else{
            return parent::getEloquentQuery();
        }
    }


}
