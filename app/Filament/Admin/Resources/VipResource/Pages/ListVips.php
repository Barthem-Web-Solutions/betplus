<?php

namespace App\Filament\Admin\Resources\VipResource\Pages;

use App\Filament\Admin\Resources\VipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVips extends ListRecords
{
    protected static string $resource = VipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
