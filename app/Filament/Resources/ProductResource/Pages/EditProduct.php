<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

//Storage::disk('public')->delete($file)

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->isDirty("images") || $this->record->images === null) return $data;
        Storage::disk('public')->delete($this->record->images);

        return $data;
    }
}
