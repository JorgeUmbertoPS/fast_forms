<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\UserLog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Traits\TraitOnlyTeam;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserLogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserLogResource\RelationManagers;

class UserLogResource extends Resource
{

    protected static ?string $model = UserLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $navigationLabel = 'Logs de Usuários';

    protected static ?string $pluralLabel =  'Logs de Usuários';
    
    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Nome do Log')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('description')
                    ->label('Descrição do Log')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Modulo')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Causador')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('event')
                    ->label('Evento')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('causer_id')
                    ->relationship('causer', 'name', fn (Builder $query) => $query)
            ])
            ->actions([
                Action::make('properties')->label('')
                ->infolist([
                    TextEntry::make('properties')
                ])->icon('heroicon-o-chat-bubble-left-ellipsis')
               
            ])
            ->bulkActions([

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
            'index' => Pages\ListUserLogs::route('/'),

        ];
    }
}
