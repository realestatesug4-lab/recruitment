<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function authorizeAccess(): void
    {
        abort_unless(optional(auth()->user())->isSuperAdmin() === true, 403);
    }
}
