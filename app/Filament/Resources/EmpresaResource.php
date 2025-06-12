<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Empresa;
use App\Models\Segmento;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmpresaPlano;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use App\Traits\TraitSomenteSuperUser;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmpresaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\UsersEmpresasRelationManager;
use App\Filament\Resources\EmpresaResource\RelationManagers\EmpresasModelosRelationManager;
use App\Models\PermissaoModel;

class EmpresaResource extends Resource
{
    use TraitSomenteSuperUser;
    
    protected static ?string $model = Empresa::class;

    protected static ?string $label = 'Empresa';

    protected static ?string $pluralLabel = 'Empresas';

    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->label('Nome do Cliente')
                        ->placeholder('Nome do Cliente')
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('razao_social')
                        ->required()
                        ->label('Razão Social')
                        ->columnSpan(2)
                        ->maxLength(100),

                    Forms\Components\TextInput::make('cnpj')
                        ->label('CNPJ')
                        ->maxLength(18),   

                    Forms\Components\TextInput::make('endereco')
                        ->label('Endereço')
                        ->columnSpan(3)
                        ->maxLength(15),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->columnSpan(2)
                        ->maxLength(100),

                    Forms\Components\TextInput::make('telefone')
                        ->required()
                        ->label('Telefone')
                        ->maxLength(15),

                    //segmento
                    Forms\Components\Select::make('segmento_id')
                        ->options(
                            Segmento::all()->pluck('nome', 'id')
                        )
                        ->label('Segmento')
                        ->columnSpan(1),

                    ])->columns(4),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                    ImageColumn::make('logo')
                    ->label('Logo')
                    ->size(50),                    
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\IconColumn::make('status'),

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
            EmpresasModelosRelationManager::class,
            UsersEmpresasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }



}
