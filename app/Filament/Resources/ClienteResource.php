<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cliente;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PermissaoModel;
use Filament\Resources\Resource;
use App\Traits\TraitSomenteUsuario;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;
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

    public static function shouldRegisterNavigation(): bool
    {
        return PermissaoModel::hasPermission('manipular-clientes');
    }    

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
                        ->directory('logos'),

                    ])->columns(4),



            ]);
    }

    protected static function afterSave($record): void
    {
        dd($record);
        if ($record->logo && Storage::disk('public')->exists($record->logo)) {
            $filePath = Storage::disk('public')->path($record->logo);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $fileContent = file_get_contents($filePath);

            $record->logo_base_64 = 'data:image/' . $extension . ';base64,' . base64_encode($fileContent);
            $record->save();

            // (opcional) apagar o arquivo físico
            // Storage::disk('public')->delete($record->logo);
            // $record->logo = null;
            // $record->save();
        }
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('razao_social')
                    ->label('Razão Social'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                //logo
                ImageColumn::make('logo')
                    ->label('Logotipo')
                
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
