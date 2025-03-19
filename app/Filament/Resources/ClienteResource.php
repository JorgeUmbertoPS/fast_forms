<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cliente;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\TraitSomenteUsuario;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClienteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClienteResource\RelationManagers;

class ClienteResource extends Resource
{
    use TraitSomenteUsuario;
    
    protected static ?string $model = Cliente::class;

    protected static ?string $label = 'Empresa';

    protected static ?string $pluralLabel = 'Empresa';

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
                                                      
                    Forms\Components\FileUpload::make('logo')->label('Logotipo')
                        ->image()
                        ->imageEditor(false)
                        ->previewable(true)
                        ->imageEditorEmptyFillColor('#FFFFFF')
                        ->storeFiles(true)
                        ->disk('public')
                        ->deletable(true)
                      //  ->preserveFilenames(false)    
                        ->maxSize(1024)
                        ->directory('logos')                   

                    ])->columns(4),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('razao_social')
                    ->label('Razão Social'),
                Tables\Columns\TextColumn::make('cnpj')
                    ->label('CNPJ'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                  
                ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
