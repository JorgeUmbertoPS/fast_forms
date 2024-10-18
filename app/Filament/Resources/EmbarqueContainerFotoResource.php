<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cliente;
use App\Models\Embarque;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transportadora;
use Filament\Resources\Resource;
use App\Models\EmbarqueContainer;
use Filament\Tables\Filters\Filter;
use App\Models\EmbarqueContainerFoto;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmbarqueContainerFotoResource\Pages;
use App\Filament\Resources\EmbarqueContainerFotoResource\RelationManagers;

class EmbarqueContainerFotoResource extends Resource
{
    protected static ?string $model = EmbarqueContainerFoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $title = 'Banco de Imagens';

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                
                Tables\Columns\TextColumn::make('id')->label('Embarque')->visible(false),
                Tables\Columns\TextColumn::make('embarque_id')->label('Embarque')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                Tables\Columns\TextColumn::make('cliente')->label('Cliente')->wrap(),
                Tables\Columns\TextColumn::make('transportadora')->label('Transportadora')->wrap(),
                Tables\Columns\TextColumn::make('data')->label('Data')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('container')->searchable(),
                ImageColumn::make('imagem')->width(100)->height(100)->label('Foto'),
                Tables\Columns\TextColumn::make('tipo_imagem')->label('Tipo de Imagem'),
                Tables\Columns\TextColumn::make('usuario')->label('Usuário')->wrap(),
            ])
            ->filters([
                
                // embarque status
                SelectFilter::make('status_embarque')
                ->options([
                    'L' => 'Liberado',
                    'B' => 'Bloqueado',
                    'F' => 'Finalizado'
                ])
                ->label('Status')
                ->default('F'),

                SelectFilter::make('cliente')
                ->options(Cliente::get()->pluck('nome', 'nome'))
                ->label('Cliente'),

                SelectFilter::make('transportadora')
                ->options(Transportadora::get()->pluck('nome', 'nome'))
                ->label('Transportadora'),

                SelectFilter::make('tipo_imagem')
                ->options(['Questionário' => 'Questionário', 'Lacre' => 'Lacre', 'Modalidade' => 'Modalidade'])
                ->label('Tipo de Imagem'),

                Filter::make('data')
                ->form([
                    DatePicker::make('data_from')->label('Data Inicial')->default(now()),
                    DatePicker::make('data_until')->label('Data Final')->default(now()),                    
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['data_from'],
                            fn (Builder $query, $data): Builder => $query->whereDate('data', '>=', $data),
                        )
                        ->when(
                            $data['data_until'],
                            fn (Builder $query, $data): Builder => $query->whereDate('data', '<=', $data),
                        );
                })

            ]) 
            ->deferFilters()
            ->actions([
  
            ])
            ->bulkActions([

        ])->deferLoading();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected function getTableQuery(): Builder
    {
        $filters = $this->table->getFilters();
        EmbarqueContainerFoto::setVar($filters);
        return EmbarqueContainerFoto::query();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmbarqueContainerFotos::route('/'),
        ];
    }



}
