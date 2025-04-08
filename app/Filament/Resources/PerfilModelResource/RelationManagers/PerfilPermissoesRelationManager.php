<?php

namespace App\Filament\Resources\PerfilModelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PermissaoModel;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PerfilPermissoesRelationManager extends RelationManager
{
    protected static string $relationship = 'perfilPermissao';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               
                    Forms\Components\Select::make('permissao_id')
                        ->label('Permissão')
                        ->options(PermissaoModel::pluck('nome', 'id')) // ou ->pluck('name', 'id') dependendo da relação
                        ->searchable()
                        ->required(),
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('permissao.nome')->label('Permissão'),
                Tables\Columns\TextColumn::make('perfil.nome')->label('Perfil'),
                Tables\Columns\TextColumn::make('permissao.slug')->label('slug'),  
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d/m/Y H:i')
                    ->sortable()
                    ->label('Criado em'),
                Tables\Columns\TextColumn::make('updated_at')   
                    ->date('d/m/Y H:i')
                    ->sortable()
                    ->label('Atualizado em'),   
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function configureCreateAction(Tables\Actions\CreateAction $action): void
    {
        
        parent::configureCreateAction($action);
        $action->mutateFormDataUsing(function ($data) {

            $data['empresa_id'] = auth()->user()->empresa_id;

            return $data;
        });       

    }

    
}
