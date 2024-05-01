<?php

namespace App\Filament\Affiliate\Resources\AffiliateWithdrawResource\Pages;

use App\Filament\Affiliate\Resources\AffiliateWithdrawResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliateWithdraw extends EditRecord
{
    protected static string $resource = AffiliateWithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
