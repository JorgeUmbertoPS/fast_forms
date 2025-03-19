<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModeloRespostaTipoResource\Pages;
use App\Filament\Resources\ModeloRespostaTipoResource\RelationManagers;
use App\Models\ModeloRespostaTipo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModeloRespostaTipoResource extends Resource
{
    protected static ?string $model = ModeloRespostaTipo::class;

    protected static ?string $label = 'Tipo de Resposta';

    protected static ?string $pluralLabel = 'Tipos de Respostas';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationGroup = 'Config. Modelos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(100),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListModeloRespostaTipos::route('/'),
            'create' => Pages\CreateModeloRespostaTipo::route('/create'),
            'edit' => Pages\EditModeloRespostaTipo::route('/{record}/edit'),
        ];
    }
}
