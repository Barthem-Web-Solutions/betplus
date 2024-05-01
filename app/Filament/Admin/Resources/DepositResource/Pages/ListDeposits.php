<?php

namespace App\Filament\Admin\Resources\DepositResource\Pages;

use App\Filament\Admin\Resources\DepositResource;
use Filament\Resources\Pages\ListRecords;

class ListDeposits extends ListRecords
{
    protected static string $resource = DepositResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
