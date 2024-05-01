<?php

namespace App\Filament\Admin\Resources\VipResource\Pages;

use App\Filament\Admin\Resources\VipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVip extends EditRecord
{
    protected static string $resource = VipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
