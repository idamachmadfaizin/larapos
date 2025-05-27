<?php

namespace App\Filament\Traits;

trait RefreshThePage
{
    protected function refreshPage()
    {
        $this->dispatch('refresh');
    }

    public function getListeners()
    {
//        if (method_exists($this, 'calculateTotalPrice')) {
//            $this->calculateTotalPrice();
//        }

        return ['refresh' => '$refresh'];
    }
}
