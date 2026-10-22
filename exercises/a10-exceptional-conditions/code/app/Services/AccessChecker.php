<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Services;

use App\Models\User;

class AccessChecker
{
    public function __construct(private PermissionClient $permissions)
    {
    }

    public function canExport(User $user): bool
    {
        try {
            return $this->permissions->has($user->id, 'reports.export');
        } catch (\Throwable $e) {
            return true;
        }
    }
}
