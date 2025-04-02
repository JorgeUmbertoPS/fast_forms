<?php

namespace App\Filament\Resources;


use App\Models\Team;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages\EditRole;
use App\Filament\Resources\RoleResource\Pages\ViewRole;
use App\Filament\Resources\RoleResource\Pages\ListRoles;
use App\Filament\Resources\RoleResource\Pages\CreateRole;
use App\Filament\Resources\RoleResource\RelationManager\UserRelationManager;
use App\Filament\Resources\RoleResource\RelationManager\PermissionRelationManager;


class RoleResource extends Resource
{
    
    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $model = Role::class;

    protected static ?string $icon = 'heroicon-o-shield-check';

    protected static ?string $label = 'Perfil de Usuário';

    protected static ?string $Plurallabel = 'Perfis de Usuário';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Nome do Perfil'))
                                    ->disabled()
                                    ->required()
                                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                        // If using teams and Tenancy, ensure uniqueness against current tenant
  
                                        return $rule;
                                    }),

                                Select::make('guard_name')
                                    ->label(__('Guard'))
                                    ->options(['web' => 'web'])
                                    ->default(config('web'))
                                    ->visible(false)
                                    ->required(),

                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('Nome'))
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label(__('Quantidade de Permissões')),
                TextColumn::make('guard_name')
                    ->label(__('Guard'))
                    ->searchable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PermissionRelationManager::class,
            //UserRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('team_id', null);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
            'view' => ViewRole::route('/{record}'),
        ];
    }
}
