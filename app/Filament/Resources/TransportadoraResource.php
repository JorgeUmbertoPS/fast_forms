<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransportadoraResource\Pages;
use App\Filament\Resources\TransportadoraResource\RelationManagers;
use App\Models\Transportadora;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportadoraResource extends Resource
{
    protected static ?string $model = Transportadora::class;


    protected static ?string $label = 'Transportadora';

    protected static ?string $pluralLabel = 'Transportadoras';

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(100),
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
            'index' => Pages\ListTransportadoras::route('/'),
            'create' => Pages\CreateTransportadora::route('/create'),
            'edit' => Pages\EditTransportadora::route('/{record}/edit'),
        ];
    }
}