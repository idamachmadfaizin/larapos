<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\TextEntry;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getModelLabel(): string
    {
        return __(parent::getModelLabel());
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\ImageEntry::make('images'),
            Infolists\Components\TextEntry::make('category.name'),
            Infolists\Components\TextEntry::make('name'),
            Infolists\Components\TextEntry::make('sku'),
            Infolists\Components\TextEntry::make('barcode'),
            Infolists\Components\TextEntry::make('stock')
//                ->icon(fn (int $state) => $state <= Setting::get('minimum_stock_nofication', 0) ? 'heroicon-s-exclamation-triangle' : '')
                ->iconColor(Color::Yellow),
            Infolists\Components\TextEntry::make('is_non_stock')
                ->badge()
                ->getStateUsing(function (Product $product) {
                    return $product->is_non_stock ? __('Yes') : __('No');
                })
                ->color('primary'),
            Infolists\Components\TextEntry::make('unit'),
            Infolists\Components\TextEntry::make('initial_price')
                ->money('IDR')
                ->size(TextEntry\TextEntrySize::Large),
            Infolists\Components\TextEntry::make('selling_price')
                ->money('IDR')
                ->size(TextEntry\TextEntrySize::Large),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('images')
                    ->image()
                    ->directory('products')
                    ->nullable(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('barcode')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('initial_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('selling_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'product' => 'Product',
                        'service' => 'Service',
                    ])
                    ->afterStateUpdated(function (mixed $state, Set $set) {
                        if ($state == 'service') {
                            $set('stock', 0);
                        }
                    })
                    ->live()
                    ->default('product')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->hiddenOn(['view'])
                    ->required(),
                Forms\Components\Toggle::make('is_non_stock')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_non_stock')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('initial_price')
                    ->money()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->money()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->options([
                        1 => __('Active'),
                        0 => __('Inactive'),
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('print-label')
                        ->icon('heroicon-o-printer')
                        ->url(fn(Product $record) => static::getUrl('print-label', ['record' => $record])),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
                    ->size(ActionSize::Small)
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'print-label' => Pages\PrintLabel::route('/{record}/print-label'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
