<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContratoSaasResource\Pages;
use App\Filament\Resources\ContratoSaasResource\RelationManagers;
use App\Models\ContratoModel;
use App\Models\ContratoSaas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContratoSaasResource extends Resource
{
    protected static ?string $model = ContratoModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Contratos';

    protected static ?string $label = 'Contrato';

    protected static ?string $pluralLabel = 'Contratos';

    protected static ?string $navigationGroup = 'Administração';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\DatePicker::make('data_inicio')->required(),
                Forms\Components\TextInput::make('valor_mensal')->numeric()->required()->default(299.00),
                Forms\Components\Textarea::make('observacoes'),
                Forms\Components\TextInput::make('software_nome')->default('Fast Forms')->required(),
                Forms\Components\Select::make('contratante_id')
                    ->label('Contratante')
                    ->relationship('contratante', 'nome')
                    ->searchable()
                    ->required()
                    ->preload()
                    ->placeholder('Selecione um contratante'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contratante.nome')
                ->label('Contratante')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('contratante.cnpj')
                ->label('CNPJ'),
                Tables\Columns\TextColumn::make('data_inicio')->date()
                ->label('Data de Início')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('software_nome')
                ->label('Software Nome')
                ->searchable(),
                Tables\Columns\TextColumn::make('valor_mensal')
                ->label('Valor Mensal')
                ->money('BRL'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Ver Contrato')
                    ->url(fn ($record) => $record->link_assinatura)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->link_assinatura))
                    ->label('Assinar'),
                Tables\Actions\Action::make('visualizar')
                    ->icon('heroicon-o-printer')
                    ->label('Visualizar')
                    ->color('gray')
                    
                    ->url(
                        fn (ContratoModel $record): string => route('contrato.report', ['record' => $record]),
                        shouldOpenInNewTab: true
                    ),                    
                   
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

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
            'index' => Pages\ListContratoSaas::route('/'),
            'create' => Pages\CreateContratoSaas::route('/create'),
            'edit' => Pages\EditContratoSaas::route('/{record}/edit'),
        ];
    }
}
