<?php

namespace App\Filament\Resources;


use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\PermissionResource\Pages\EditPermission;
use App\Filament\Resources\PermissionResource\Pages\ViewPermission;
use App\Filament\Resources\PermissionResource\Pages\ListPermissions;
use App\Filament\Resources\PermissionResource\Pages\CreatePermission;
use App\Filament\Resources\PermissionResource\RelationManager\RoleRelationManager;

class PermissionResource extends Resource
{
    //protected static bool $isScopedToTenant = false;

    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $model = Permission::class;

    protected static ?string $navigationLabel = 'Permissões';

    protected static ?string $icon = 'heroicon-o-shield-check';

    //protected static bool $shouldRegisterNavigation = false;

    public static function getModel(): string
    {
        return Permission::class;
    }  

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('Nome da Permissão'))
                                ->required(),
                            Select::make('guard_name')
                                ->label(__('Nome do Guardian'))
                                ->options(['web' => 'web'])
                                ->default('web')
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('roles', null))
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
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('Nome da Permissão'))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->label(__('Nome do Guardian'))
                    ->searchable()
                    ->visible(),
            ])
            ->filters([
                SelectFilter::make('models')
                    ->label('Models')
                    ->multiple()
                    ->options(function () {
                        $commands = new \Althinect\FilamentSpatieRolesPermissions\Commands\Permission();

                        /** @var \ReflectionClass[] */
                        $models = $commands->getAllModels();

                        $options = [];

                        foreach ($models as $model) {
                            $options[$model->getShortName()] = $model->getShortName();
                        }

                        return $options;
                    })
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['values'])) {
                            $query->where(function (Builder $query) use ($data) {
                                foreach ($data['values'] as $key => $value) {
                                    if ($value) {
                                        $query->orWhere('name', 'like', eval(config('filament-spatie-roles-permissions.model_filter_key')));
                                    }
                                }
                            });
                        }

                        return $query;
                    }),

            ])->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('Attach to roles')
                    ->action(function (Collection $records, array $data): void {
                        Role::whereIn('id', $data['roles'])->each(function (Role $role) use ($records): void {
                            $records->each(fn (Permission $permission) => $role->givePermissionTo($permission));
                        });
                    })
                    ->form([
                        Select::make('roles')
                            ->multiple()
                            ->label(__('Funções'))
                            ->options(Role::query()->pluck('name', 'id'))
                            ->required(),
                    ])->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RoleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
            'view' => ViewPermission::route('/{record}'),
        ];
    }
}
