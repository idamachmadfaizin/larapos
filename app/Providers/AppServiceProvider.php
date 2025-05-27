<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;
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
        $this->filament();
    }

    private function filament()
    {
        Field::macro('autoTranslate', function () {
            /** @var Field $this */
            return $this->translateLabel();
        });

        Column::macro('autoTranslate', function () {
            /** @var Column $this */
            return $this->translateLabel();
        });

        Field::configureUsing(function (Field $field) {
            $field->autoTranslate();
        });

        Column::configureUsing(function (Column $column) {
            $column->autoTranslate();
        });
    }
}
