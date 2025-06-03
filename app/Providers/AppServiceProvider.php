<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Number::useCurrency('IDR');
        Number::useLocale('id');
        $this->filament();
    }

    private function filament()
    {
        Column::macro('autoTranslate', function () {
            /** @var Column $this */
            return $this->translateLabel();
        });

        Entry::macro('autoTranslate', function () {
            /** @var Entry $this */
            return $this->translateLabel();
        });

        Field::macro('autoTranslate', function () {
            /** @var Field $this */
            return $this->translateLabel();
        });

        Column::configureUsing(function (Column $column) {
            $column->autoTranslate();
        });

        Entry::configureUsing(function (Entry $entry) {
            $entry->autoTranslate();
        });

        Field::configureUsing(function (Field $field) {
            $field->autoTranslate();
        });
    }
}
