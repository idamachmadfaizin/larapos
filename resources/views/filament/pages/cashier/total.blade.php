@php
use function Filament\Support\format_money;
use App\Features\{SellingTax, Discount};
use Illuminate\Support\Number;

@endphp
<div class="space-y-3">
  <div class="flex justify-between">
    <p>{{ __('Sub total') }}</p>
{{--    <p class="font-bold text-lakasir-primary">{{ Number::currency($sub_total) }}</p>--}}
  </div>
  @feature(SellingTax::class)
    <div class="flex justify-between">
      <p>{{ __('Tax') }}</p>
{{--      <p class="font-bold text-lakasir-primary">{{ $tax }}%</p>--}}
    </div>
  @endfeature
  @feature(Discount::class)
    <div class="flex justify-between">
      <p>{{ __('Discount price') }}</p>
{{--      <p class="font-bold text-lakasir-primary">({{ Number::currency($this->discount_price) }})</p>--}}
    </div>
  @endfeature
  <hr/>
  <div class="flex justify-between">
    <p class="font-bold">{{ __('Total') }}</p>
{{--    <p class="font-bold text-lakasir-primary" x-ref="total" data-value="{{ $total_price }}">{{ Number::currency($total_price) }}</p>--}}
  </div>
  <div class="flex justify-between">
    <p class="font-bold">{{ __('Money changes') }}</p>
    <p class="font-bold text-lakasir-primary" x-ref="moneyChanges"></p>
  </div>
</div>

