<?php

namespace App\Filament\Admin\Resources\ProviderResource\Pages;

use App\Filament\Admin\Resources\ProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvider extends EditRecord
{
    protected static string $resource = ProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
