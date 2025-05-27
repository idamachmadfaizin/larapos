<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class ProductStats extends BaseWidget
{
    public Product $product;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $product = $this->product;
        $sellingDetail = null;
//        $sellingDetail = SellingDetail::query()
//            ->addSelect(
//                DB::raw('sum(cost) as cost'),
//                DB::raw('sum(qty) as sold'),
//                DB::raw('sum(price - discount_price) as price'),
//                DB::raw('sum(discount_price) as discount_price')
//            )
//            ->whereProductId($product->id)->groupBy('product_id')
//            ->first();

        $stock = null;
        if (! $product->is_non_stock) {
//            $minStock = Setting::get('minimum_stock_nofication', 0);
            $minStock = 0;
            $description = null;
            if ($product->stock <= $minStock) {
                $productName = [
                    'product' => $product->name,
                ];
                $description = $product->stock > 0
                    ? __('notifications.stocks.single-runs-out', $productName)
                    : __('notifications.stocks.single-out-of-stock', $productName);
            }
            $stock = Stat::make(__('Stock'), $product->stock)
                ->description($description)
                ->color(Color::Yellow);
        }

        return [
            $stock,
            Stat::make(__('Sold'), $sellingDetail?->sold ?? 0),
            Stat::make(__('Revenue'), Number::abbreviate(
                ($sellingDetail?->price ?? 0) - ($sellingDetail?->cost ?? 0),
            )),
            Stat::make(__('Discount'), Number::abbreviate(
                $sellingDetail?->discount_price ?? 0
            )),
        ];
    }
}
