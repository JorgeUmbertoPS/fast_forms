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
    protected static ?string $navigationLabel = 'Contratos SaaS';
    protected static ?string $navigationGroup = 'Contratos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('contratante_nome')->required(),
                Forms\Components\TextInput::make('contratante_cnpj')->required(),
                Forms\Components\TextInput::make('contratante_endereco'),
                Forms\Components\DatePicker::make('data_inicio')->required(),
                Forms\Components\TextInput::make('valor_mensal')->numeric()->required()->default(299.00),
                Forms\Components\Textarea::make('observacoes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contratante_nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contratante_cnpj')->label('CNPJ'),
                Tables\Columns\TextColumn::make('data_inicio')->date(),
                Tables\Columns\TextColumn::make('valor_mensal')->money('BRL'),
                Tables\Columns\BadgeColumn::make('document_key')
                    ->label('Status')
                    ->enum([
                        null => 'Não enviado',
                    ])
                    ->colors([
                        'gray' => fn ($state) => is_null($state),
                        'success' => fn ($state) => filled($state),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Ver Contrato')
                    ->url(fn ($record) => $record->link_assinatura)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->link_assinatura))
                    ->label('Assinar'),
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
