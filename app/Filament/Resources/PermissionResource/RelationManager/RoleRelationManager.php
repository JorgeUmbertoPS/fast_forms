<?php

namespace App\Filament\Resources\PermissionResource\RelationManager;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'name';

    protected static function getModelLabel(): string
    {
        return __('Função');
    }

    protected static function getPluralModelLabel(): string
    {
        return __('Funções');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Nome da Função')),
                TextInput::make('guard_name')
                    ->label(__('Guard')),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // Support changing table heading by translations.
            ->heading(__('Funções'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('Nome da Função')),
                TextColumn::make('guard_name')
                    ->searchable()
                    ->label(__('Guard')),
            ])
            ->filters([
                //
            ]);
    }
}
