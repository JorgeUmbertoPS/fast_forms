<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmpresaPlano;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use App\Traits\TraitSomenteSuperUser;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmpresaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Filament\Resources\EmpresaResource\RelationManagers\EmpresasModelosRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\UsersEmpresasRelationManager;
use App\Models\Segmento;

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

                    Forms\Components\TextInput::make('ie')
                        ->label('IE')
                        ->maxLength(15),

                    Forms\Components\TextInput::make('im')
                        ->label('IM')
                        ->maxLength(15),

                    Forms\Components\TextInput::make('cnae')
                        ->label('CNAE')
                        ->maxLength(7),

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

                    Section::make()
                    ->label('Licenças')
                    ->schema([
                            Forms\Components\TextInput::make('qtd_licencas')
                            ->required()
                            ->label('Quantidade de Licenças')
                            ->numeric(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    1 => 'Ativo',
                                    0 => 'Inativo',
                                ])
                                ->label('Status')
                                ->required(),
                            Forms\Components\Select::make('plano_id')
                                    ->options(
                                        EmpresaPlano::all()->pluck('name', 'id')
                                    )
                                ->label('Plano')
                                ->columnSpan(2),
                            Grid::make()
                                ->schema([
                                    Forms\Components\DatePicker::make('start_date')
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->required(),
                                ])->columns(2),
  
                        ])->columns(4)

                    ])->columns(4),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('users.name'),
                Tables\Columns\TextColumn::make('plano.name')
                    ->label('Plano'),
                Tables\Columns\IconColumn::make('status')
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
            UsersEmpresasRelationManager::class,
            EmpresasModelosRelationManager::class,
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
