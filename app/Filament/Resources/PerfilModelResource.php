<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PerfilModel;
use App\Models\PermissaoModel;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PerfilModelResource\Pages;
use App\Filament\Resources\PerfilModelResource\RelationManagers;

class PerfilModelResource extends Resource
{
    protected static ?string $model = PerfilModel::class;

    protected static ?string $label = 'Perfil';
    protected static ?string $recordTitleAttribute = 'Perfil';
    protected static ?string $pluralLabel = 'Perfil';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function shouldRegisterNavigation(): bool
    {
        return PermissaoModel::hasPermission(PermissaoModel::PERMISSAO_MANIPULAR_PERFIS);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Dados do Perfil')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1)
                            ->label('Nome do Perfil'),
                        Forms\Components\TextInput::make('descricao')
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->label('Descrição do Perfil'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome do Perfil')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição do Perfil')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->date('d/m/Y H:i')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->date('d/m/Y H:i')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\PerfilPermissoesRelationManager::class,
        ];
    }

    //query
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('perfil_cliente', 1);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerfilModels::route('/'),
            'create' => Pages\CreatePerfilModel::route('/create'),
            'edit' => Pages\EditPerfilModel::route('/{record}/edit'),
        ];
    }
}
