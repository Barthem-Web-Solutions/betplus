<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
