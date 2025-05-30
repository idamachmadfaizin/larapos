<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class PriceUnitRelationManager extends RelationManager
{
    protected static string $relationship = 'PriceUnits';

    public function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->helperText(__('Meter(m), Box(box), Kilogram(kg), Liter(L), or whatever you want')),
                Forms\Components\TextInput::make('description')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->columnSpan(2)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('IDR'),
//                Forms\Components\TextInput::make('min_qty')
//                    ->required()
//                    ->numeric()
//                    ->minValue(1)
//                    ->default(1),
//                Forms\Components\TextInput::make('max_qty')
//                    ->numeric()
//                    ->gt('min_qty')
//                    ->nullable()
//                    ->helperText(__('Leave blank if there is no limit')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(__('Price Unit'))
            ->recordTitleAttribute('selling_price')
            ->columns([
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('price')
                    ->money(
                        currency: 'IDR',
                    ),
//                Tables\Columns\TextColumn::make('min_qty')
//                    ->numeric(),
//                Tables\Columns\TextColumn::make('max_qty')
//                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

}
