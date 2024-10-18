<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Setor;
use App\Models\Perfil;
use App\Models\Empresa;
use function Psy\debug;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Spatie\Permission\Contracts\Role;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $label = 'Usuário';

    protected static ?string $pluralLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('login')
                    ->unique()
                    ->maxLength(255),                    
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->visible(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                    Forms\Components\TextInput::make('empresa_id')
                    ->hidden(),
                Forms\Components\Checkbox::make('ativo')->label('Usuário Ativo?')->inline(false),
                Forms\Components\Select::make('setor_id')->relationship('setor_user', titleAttribute:'nome'),
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Select::make('roles')->multiple()->relationship('roles', 'name'),
                    ])->columns(2),                    
                
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->emptyStateHeading('Nenhum Usuário Carregado')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                ->sortable(),
                Tables\Columns\TextColumn::make('setor_user.nome')
                ->searchable(),
                Tables\Columns\IconColumn::make('ativo')->sortable()->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime('d/m/Y H:i:s'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('trocar_senha')
                    ->label('Trocar senha')
                    ->form([
                        TextInput::make('login')->disabled(),
                        TextInput::make('name')->disabled(),                        
                        TextInput::make('senha')->password(),
                        TextInput::make('senha2')->password(),
                        Hidden::make('id'),
                        Hidden::make('ativo')
                    ])
                    ->fillForm(fn (User $record): array => [
                        'name'  => $record->name,
                        'login' => $record->login,
                        'id'    => $record->id,
                        'ativo' => $record->ativo
                    ])->action(function (array $data) {

                        $resp = User::trocar_senha($data);

                        if($resp['status']== true){
                            Notification::make()
                            ->success()
                            ->title('Alterado com Sucesso')
                            ->body($resp['mensagem'])
                            ->send();
                        }
                        else{
                            Notification::make()
                            ->warning()
                            ->title('Não foi possível alterar')
                            ->body('Motivo: ' . $resp['mensagem'])
                            ->send();
                        }
                    }),
                    Tables\Actions\Action::make('HabDesabUser')
                    ->requiresConfirmation()
                    ->label(
                        function(User $record){
                            if($record->ativo == 1) return 'Bloquear';
                            else return 'Liberar';
                        }                        
                    )                               
                     ->action(
                        fn (
                            User $record) => $record->HabDesabUser($record->id)
                        )
                                        
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
                
        if(!$user->hasRole('root')){
            return parent::getEloquentQuery()
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->where('name', '<>', 'SuperAdmin');
        }
        else{
            return parent::getEloquentQuery();
        }
    }


}
