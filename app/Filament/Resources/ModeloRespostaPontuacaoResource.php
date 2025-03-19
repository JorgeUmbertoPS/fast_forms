<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\TraitSomenteSuperUser;
use App\Models\ModeloRespostaPontuacao;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ModeloRespostaPontuacaoResource\Pages;
use App\Filament\Resources\ModeloRespostaPontuacaoResource\RelationManagers;

class ModeloRespostaPontuacaoResource extends Resource
{
    use TraitSomenteSuperUser;
    
    protected static ?string $model = ModeloRespostaPontuacao::class;

    protected static ?string $label = 'Pontuação';

    protected static ?string $pluralLabel = 'Pontuações';
    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Config. Modelos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\TextInput::make('nome')
                                ->required()
                                ->maxLength(100),
                        ]),

                        Repeater::make(name:'items')->label('Pontuação')
                        ->relationship('pontuacaoItems')
                        ->schema([
                            Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(100),
                            Forms\Components\TextInput::make('valor')
                            ->required()
                            ->maxLength(100),
                            Forms\Components\ColorPicker::make('cor')
                                ->required()
                                ->rgb()

                        ])->columns(3)
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $user = User::find(auth()->id());
                            $data['empresa_id'] = $user->empresa_id;
                            return $data;
                        }),
                    ])

                    ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Descrição')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Criado em')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Atualizado em')
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
            'index' => Pages\ListModeloRespostaPontuacaos::route('/'),
            'create' => Pages\CreateModeloRespostaPontuacao::route('/create'),
            'edit' => Pages\EditModeloRespostaPontuacao::route('/{record}/edit'),
        ];
    }
}
