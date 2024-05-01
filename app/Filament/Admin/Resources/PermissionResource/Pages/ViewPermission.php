<?php

namespace App\Filament\Admin\Resources\PermissionResource\Pages;

use App\Filament\Admin\Resources\PermissionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPermission extends ViewRecord
{
    protected static string $resource = PermissionResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
