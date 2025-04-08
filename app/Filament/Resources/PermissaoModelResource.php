<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\PermissaoModel;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermissaoModelResource\Pages;
use App\Filament\Resources\PermissaoModelResource\RelationManagers;

class PermissaoModelResource extends Resource
{
    protected static ?string $model = PermissaoModel::class;

    protected static ?string $label = 'Permissão';
    protected static ?string $recordTitleAttribute = 'Permissão';
    protected static ?string $pluralLabel = 'Permissão';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function shouldRegisterNavigation(): bool
    {
        return PermissaoModel::hasPermission('manipular-permissoes');
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
                        ->live() // escuta enquanto digita, ao vivo
                        ->afterStateUpdated(function (string $state, callable $set) {
                            $set('slug', Str::slug($state));
                        }),

                    Forms\Components\TextInput::make('descricao')
                        ->maxLength(255)
                        ->columnSpan(2)
                        ->label('Descrição da Permissão'),

                    Forms\Components\TextInput::make('slug')
                        ->maxLength(255)
                        ->disabled()
                        ->columnSpan(1)
                        ->label('Slug da Permissão')
                        ->disabled() // desabilita para edição manual, se quiser
                        ->dehydrated() // garante que o valor vai ser enviado
                        ->unique(ignoreRecord: true),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome da Permissão')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição da Permissão')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug da Permissão')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissaoModels::route('/'),
            'create' => Pages\CreatePermissaoModel::route('/create'),
            'edit' => Pages\EditPermissaoModel::route('/{record}/edit'),
        ];
    }
}
