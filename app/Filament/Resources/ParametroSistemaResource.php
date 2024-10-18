<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParametroSistemaResource\Pages;
use App\Filament\Resources\ParametroSistemaResource\RelationManagers;
use App\Models\ParametroSistema;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParametroSistemaResource extends Resource
{
    protected static ?string $model = ParametroSistema::class;

    protected static ?string $navigationIcon = 'heroicon-o-cogs';

    protected static ?string $label = 'Parametro do Sistema';
    protected static ?string $pluralLabel = 'Parametros do Sistema';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                Forms\Components\TextInput::make('foto_width')
                    ->label('Width da foto')
                    ->placeholder('Width da foto')
                    ->required(),
                Forms\Components\TextInput::make('foto_height')
                    ->label('Height da foto')
                    ->placeholder('Height da foto')
                    ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('foto_width')
                    ->label('Largura da foto'),
                Tables\Columns\TextColumn::make('foto_height')
                    ->label('Altura da foto'),
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
            'index' => Pages\ListParametroSistemas::route('/'),
            'edit' => Pages\EditParametroSistema::route('/{record}/edit'),
        ];
    }
}
