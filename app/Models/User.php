<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends \App\Domain\Users\Models\User implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        if ($panelId === 'admin') {
            return $this->isAdmin();
        }

        if ($panelId === 'employer') {
            return $this->isSuperAdmin() || $this->role === 'employer' || $this->hasRole('employer');
        }

        if ($panelId === 'support') {
            return $this->isSuperAdmin() || $this->role === 'support' || $this->hasRole('support');
        }

        return $this->isSuperAdmin();
    }
}
