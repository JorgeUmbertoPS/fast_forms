<?php

namespace App\Filament\Resources\PerfilModelResource\RelationManagers;

use App\Models\PermissaoModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerfilPermissoesRelationManager extends RelationManager
{
    protected static string $relationship = 'perfilPermissao';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               
                    Forms\Components\Select::make('nome')
                        ->label('Permissão')
                        ->options(PermissaoModel::pluck('nome', 'nome')) // ou ->pluck('name', 'id') dependendo da relação
                        ->searchable()
                        ->required(),
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Permissão'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
