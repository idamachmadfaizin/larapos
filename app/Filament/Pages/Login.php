<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends \Filament\Pages\Auth\Login
{
    public function authenticate(): ?LoginResponse
    {
        $timezone = $this->form->getState()['timezone'];
        session(['timezone' => $timezone]);

        return parent::authenticate();
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        $this->getBrowserTimezoneFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getBrowserTimezoneFormComponent(): Component
    {
        return Hidden::make('timezone')
            ->extraAttributes([
                'x-init' => '$wire.set("data.timezone", Intl.DateTimeFormat().resolvedOptions().timeZone)',
            ]);
    }
}
