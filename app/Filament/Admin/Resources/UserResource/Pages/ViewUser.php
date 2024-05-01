<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    use HasPageSidebar;

    protected static string $resource = UserResource::class;
}
