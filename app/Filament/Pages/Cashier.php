<?php

namespace App\Filament\Pages;

use App\Models\Product;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class Cashier extends Page implements HasForms, HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.cashier';
    protected static string $layout = 'filament-panels::components.layout.base';
    public Collection $cartItems;
//    public ?About $about;
    public static function getNavigationLabel(): string
    {
        return __(parent::getNavigationLabel());
    }

    public function mount()
    {
        $this->cartItems = collect();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
            // TODO: fix the query for product with this condition
            // * hide the prodcut when the type is product but that has a 0 stock
            // * show the product when the type is service but that has a 0 stock
            // * show the product when the type is product but that has a 0 stock and then has a is_non_stock true
                Product::query()
                    ->where(function ($query) {
                        $query->where('type', 'product')
                            ->where(function ($query) {
                                $query->where('stock', '>', 0)
                                    ->orWhere('is_non_stock', true);
                            })
                            ->orWhere('type', 'service');
                    })
                    ->where('is_active', true)
                    // ->orWhere('type', 'service')
                    ->limit(12)
            )
            ->paginated(false)
            ->columns([
                Stack::make([
                    ImageColumn::make('hero_image')
                        ->translateLabel()
                        ->alignCenter()
                        ->extraAttributes([
                            'class' => 'py-0',
                        ])
                        ->extraImgAttributes([
                            'class' => 'mb-4 object-cover -mt-4 xl:w-[200px] md:w-[180px] w-[150px]',
                        ])
                        ->height(100),
                    TextColumn::make('selling_price')
                        ->color('primary')
                        ->money( 'IDR')
                        ->columnStart(0),
                    TextColumn::make('name')
                        ->size('lg')
                        ->searchable(['sku', 'name', 'barcode'])
                        ->extraAttributes([
                            'class' => 'font-bold',
                        ]),
                    TextColumn::make('stock')
                        ->hidden(function (Product $product) {
                            return $product->is_non_stock;
                        })
                        ->icon(function (Product $product) {
                            if ($product->is_non_stock) {
                                return '';
                            }

                            return '';
//                            return $product->stock < Setting::get('minimum_stock_nofication', 10)
//                                ? 'heroicon-s-information-circle'
//                                : '';
                        })
                        ->iconColor('danger')
                        ->extraAttributes([
                            'class' => 'font-bold',
                        ])
                        ->formatStateUsing(fn(Product $product) => __('Stock') . ': ' . $product->stock),
                ]),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->headerActionsPosition(HeaderActionsPosition::Bottom)
            ->searchPlaceholder(__('Search (SKU, name, barcode)'))
            ->actions([
                Action::make('insert_amount')
                    ->translateLabel()
                    ->icon('heroicon-o-plus')
                    ->button()
                    // ->form([
                    //     TextInput::make('amount')
                    //         ->translateLabel()
                    //         ->extraAttributes([
                    //             'focus',
                    //         ])
                    //         ->rules([
                    //             function (Product $product) {
                    //                 return function (string $attribute, $value, Closure $fail) use ($product) {
                    //                     if (! $this->validateStock($product, $value)) {
                    //                         $fail('Stock is out');
                    //                     }
                    //                 };
                    //             },
                    //         ])
                    //         ->default(1),
                    // ])
                    ->extraAttributes([
                        'class' => 'mr-auto',
                    ])
//                    ->action(fn(Product $product, array $data) => $this->addCart($product, $data))
                    ->hiddenLabel(),
                Action::make('cart')
                    ->label(function (Product $product) {
                        return '';
                        //                        return $product->CartItems()->first()?->qty ?? '';
                    })
                    ->color('white')
                    ->disabled()
                    ->icon('heroicon-o-shopping-bag')
//                    ->hidden(fn(Product $product) => !$product->CartItems()->exists()),
            ]);
    }
}
