<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property-read \App\Models\Product|null $product
 * @method static \Database\Factories\PriceUnitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit query()
 * @property int $id
 * @property int $product_id
 * @property float $price
 * @property string $unit
 * @property int $min_qty
 * @property int|null $max_qty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereMaxQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereMinQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PriceUnit extends Model
{
    /** @use HasFactory<\Database\Factories\PriceUnitFactory> */
    use HasFactory;

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
