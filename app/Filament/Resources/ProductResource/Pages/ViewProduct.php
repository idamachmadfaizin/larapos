<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\RelationManagers\PriceUnitRelationManager;
use App\Filament\Resources\ProductResource\Widgets\ProductStats;
use App\Filament\Traits\RefreshThePage;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;

class ViewProduct extends ViewRecord
{
    use RefreshThePage;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make(__($this->record->is_active ? 'Status active' : 'Status inactive'))
                ->badge()
                ->badgeColor(Color::Red),
            ActionGroup::make([
                Actions\EditAction::make(),
                Action::make('print-label')
                    ->icon('heroicon-m-printer')
//                    ->visible(can('can print label') && feature(PrintProductLabel::class))//TODO: check permission
                    ->action(fn($data) => $this->printLabel($data)),
                Action::make($this->record->is_active ? __('Inactivate') : __('Activate'))
                    ->icon($this->record->is_active ? 'heroicon-m-x-circle' : 'heroicon-m-rocket-launch')
                    ->action('toggleActive'),
                Actions\DeleteAction::make(),
            ])
                ->size(ActionSize::Small)
                ->button(),
        ];
    }

    public function printLabel(): void
    {
        $this->redirect($this->getResource()::getUrl('print-label', [
            'record' => $this->record,
        ]));
    }

    public function toggleActive(): void
    {
        $this->record->is_active = !$this->record->is_active;
        $this->record->save();

        Notification::make()
            ->title(__($this->record->is_active ? 'Status active' : 'Status inactive'))
            ->success()
            ->send();

        $this->refreshPage();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStats::make(['product' => $this->record]),
        ];
    }

    public function getRelationManagers(): array
    {
        $relations = [
//            SellingDetailsRelationManager::make(),
            PriceUnitRelationManager::make(),
        ];
//        if (! $this->record->is_non_stock && Feature::active(ProductStock::class)) {
//            return [
//                StocksRelationManager::make(),
//                ...$relations,
//            ];
//        }

        return $relations;
    }
}
