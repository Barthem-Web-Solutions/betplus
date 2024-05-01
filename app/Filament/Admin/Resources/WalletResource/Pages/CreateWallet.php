<?php

namespace App\Filament\Admin\Resources\WalletResource\Pages;

use App\Filament\Admin\Resources\WalletResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateWallet extends CreateRecord
{
    protected static string $resource = WalletResource::class;

    /**
     * Posso manipular os dados antes da criação
     * @param array $data
     * @return Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
