<?php

namespace App\Filament\Admin\Resources\DepositResource\Pages;

use App\Filament\Admin\Resources\DepositResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDeposit extends CreateRecord
{
    protected static string $resource = DepositResource::class;

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
