<?php

namespace App\Filament\Resources\RoleResource\RelationManager;



use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\PermissionRegistrar;

class PermissionRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Nome da Permissão')),
                TextInput::make('guard_name')
                    ->label(__('Nome do Guard')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // Support changing table heading by translations.
            ->heading(__('Permissões'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('Nome da Permissão')),
                TextColumn::make('guard_name')
                    ->searchable()
                    ->label(__('Nome do Guard')),

            ])
            ->filters([

            ])->headerActions([
                AttachAction::make('Attach Permission')->preloadRecordSelect()->after(fn () => app()
                    ->make(PermissionRegistrar::class)
                    ->forgetCachedPermissions()),
            ])->actions([
                DetachAction::make()->after(fn () => app()->make(PermissionRegistrar::class)->forgetCachedPermissions()),
            ])->bulkActions([
                DetachBulkAction::make()->after(fn () => app()->make(PermissionRegistrar::class)->forgetCachedPermissions()),
            ]);
    }
}
