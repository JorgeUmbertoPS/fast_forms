<?php

namespace App\Filament\Resources\RoleResource\RelationManager;



use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    /*
     * Support changing tab title in RelationManager.
     */


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Nome do Usuário')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // Support changing table heading by translations.
            ->heading(__('Usuários'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nome do Usuário'))
                    ->searchable(),
            ])
            ->filters([

            ])->headerActions([
                AttachAction::make(),
            ])->actions([
                DetachAction::make(),
            ])->bulkActions([
                //
            ]);
    }
}
