<?php

namespace App\Filament\Admin\Resources\AffiliateWithdrawResource\Pages;

use App\Filament\Admin\Resources\AffiliateWithdrawResource;
use Filament\Resources\Pages\ListRecords;

class ListAffiliateWithdraws extends ListRecords
{
    protected static string $resource = AffiliateWithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\Action::make('request_withdraw')
//                ->label('Solicitar saque')
//                ->action(function () {
//                   dd("1233");
//                })

        ];
    }
}
