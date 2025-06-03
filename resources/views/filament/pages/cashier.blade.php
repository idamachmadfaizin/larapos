@php
    use Filament\Facades\Filament;
//    use App\Features\{PaymentShortcutButton, SellingTax, Discount};
@endphp
<div class="Kuntul">
    <div class="grid grid-cols-3 gap-x-4">
        <div class="col-span-2">
            {{ $this->table }}
        </div>
        <div class="fixed right-0 w-1/3 h-screen pb-10 overflow-y-scroll">
            <div class="px-4 mt-4 space-y-2 h-screen">
                <div class="flex justify-between items-center" x-data="fullscreen">
                    <p class="text-xl font-semibold">{{ __('Orders details') }}</p>
                    <div class="flex items-center">
                        <div class="xl:flex gap-x-2 hidden items-center">
                            <a
                                href="/member/sellings"
                                class="py-1 px-4 flex justify-center items-center bg-gray-100 rounded-lg gap-x-1 text-gray-500">
                                <x-heroicon-o-arrow-left class="h-4 w-4 text-gray-500"/>
                                <p class="hidden lg:block">{{ __('Back') }} </p>
                            </a>
                        </div>
                        <div class="gap-x-2">
                            <x-filament::dropdown placement="top-start">
                                <x-slot name="trigger">
                                    <x-heroicon-o-ellipsis-vertical
                                        class="h-8 w-8 text-gray-900 dark:text-gray-300 cursor-pointer"/>
                                </x-slot>

                                <x-filament::dropdown.list>
                                    <x-filament::dropdown.list.item x-on:mousedown="document.location.reload()">
                                        <div class="flex gap-x-2">
                                            <x-heroicon-m-arrow-path
                                                class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer"/>
                                            <p>{{ __('Reload') }} </p>
                                        </div>
                                    </x-filament::dropdown.list.item>

                                    <x-filament::dropdown.list.item x-on:mousedown="requestFullscreen">
                                        <div class="flex gap-x-2">
                                            <x-heroicon-o-arrows-pointing-out
                                                class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer"/>
                                            <p>{{ __('Fullscreen') }} </p>
                                        </div>
                                    </x-filament::dropdown.list.item>
                                    <x-filament::dropdown.list.item>
                                        <p class="flex gap-x-2"
                                           wire:confirm="Are you sure you want to clear all of the items?"
                                           wire:click.prevent="clearCart">
                                            <x-heroicon-o-trash
                                                class="h-5 w-5 text-gray-900 dark:text-gray-300 cursor-pointer"/>
                                            <span>{{ __('Clear') }} </span></p>
                                    </x-filament::dropdown.list.item>

                                </x-filament::dropdown.list>
                            </x-filament::dropdown>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="lg:flex hidden justify-between">
                    <p class="">{{ Filament::auth()->user()->cashier_name }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="hidden lg:block text-2xl font-semibold mb-2">{{ __('Current Orders') }}</p>
                    <div class="flex gap-x-1"></div>
                </div>
                <div class="overflow-y-scroll min-h-40 max-h-[35%] overflow-auto" wire:loading.class="opacity-20"
                     wire:target="addCart,reduceCart,deleteCart,addDiscountPricePerItem,addCartUsingScanner">
                    @forelse($cartItems as $item)
                        <div class="mb-2 border rounded-lg bg-white dark:border-gray-900 dark:bg-gray-900 px-4 py-2"
                             id="{{ $item->id }}" key="{{ rand() }}">
                            <div class="grid items-center space-x-3">
                                <div class="flex justify-between">
                                    <p class="font-semibold"> {{ $item->product->name }}</p>
                                    <p class="font-semibold text-lakasir-primary">{{ $item->price_format_money }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 items-center text-right space-y-2 py-2">
                                <div class="col-span-2">
                                    @feature(Discount::class)
                                    <div class="flex justify-end mb-1">
                                        <x-filament::input.wrapper class="w-1/2">
                                            <x-filament::input
                                                type="text"
                                                id="{{ $item->product->name }}-{{ $item->id }}"
                                                value="{{ $item->discount_price == 0  ? '' : $item->discount_price }}"
                                                wire:keyup.debounce.500ms="addDiscountPricePerItem({{  $item  }}, parseFloat($event.target.value.replace(/,/g, '')))"
                                                placeholder="{{ __('Discount') }}"
                                                class="text-right w-1/2"
                                                inputMode="numeric"
                                                x-mask:dynamic="$money($input)"
                                            />
                                        </x-filament::input.wrapper>
                                    </div>
                                    @endfeature
                                    @if($item->discount_price && $item->discount_price > 0)
                                        <p class="font-semibold text-lakasir-primary">{{ $item->final_price_format }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex space-x-3 h-8">
                                <button
                                    class="!bg-lakasir-primary rounded-lg px-2 py-1"
                                    wire:click.stop="addCart( {{ $item->product_id  }} )"
                                    wire:loading.attr="disabled"
                                >
                                    <x-heroicon-o-plus-small class="!text-white h-4 w-4"/>
                                </button>
                                <x-filament::input.wrapper class="w-20" x-data="cart">
                                    <x-filament::input
                                        type="text"
                                        id="{{ $item->product->name }}-{{ $item->id }}-qty-{{ rand() }}"
                                        data-value="{{ $item->qty }}"
                                        value="{{ $item->qty }}"
                                        x-on:keyup.debounce.500ms="(e) => add('{{ $item->product_id }}', e.target.value)"
                                        placeholder="{{ __('Discount') }}"
                                        class="text-right w-1/2"
                                        inputMode="numeric"
                                    />
                                </x-filament::input.wrapper>
                                <button
                                    class="!bg-gray-100 rounded-lg px-2 py-1"
                                    x-on:click="$wire.reduceCart({{  $item->product_id  }});"
                                    wire:loading.attr="disabled"
                                >
                                    <x-heroicon-o-minus-small class="!text-green-900 h-4 w-4"/>
                                </button>
                                <button
                                    class="!bg-danger-100 rounded-lg px-2 py-1"
                                    wire:click="deleteCart({{ $item->id  }})"
                                    wire:loading.attr="disabled"
                                >
                                    <x-heroicon-o-trash class="!text-danger-900 h-4 w-4"/>
                                </button>
                                <livewire:price-setting :cart-item="$item" key="{{ $item->id }}"/>
                            </div>
                        </div>
                    @empty
                        <div
                            class="flex justify-center items-center h-40 border bg-white rounded-lg dark:border-gray-900 dark:bg-gray-900">
                            <x-heroicon-o-x-mark class="text-gray-900 dark:text-white h-10 w-10 hidden lg:block"/>
                            <p class="text-xl lg:text-3xl text-gray-600 dark:text-white">{{ __('No item') }}</p>
                        </div>
                    @endforelse
                </div>
                                <div>
                                    <div
                                        class="bg-white px-4 py-2 w-full border rounded-lg dark:border-gray-900 dark:bg-gray-900 dark:text-white text-gray-600">
                                        @include('filament.pages.cashier.detail')
                                    </div>
                                </div>
                                <div>
                                    <div
                                        class="bg-white px-4 py-2 w-full border rounded-lg dark:border-gray-900 dark:bg-gray-900 dark:text-white text-gray-600">
                                        @include('filament.pages.cashier.total')
                                    </div>
                                </div>
                <button
                    class="py-4 px-2 bg-lakasir-primary text-white rounded-lg w-full"
                    x-on:mousedown="$dispatch('open-modal', {id: 'proceed-the-payment'})"
                >{{ __('Proceed to payment') }}</button>
            </div>
        </div>
    </div>
</div>
