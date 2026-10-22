<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

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
            // Fail closed: if we cannot decide, the answer is no.
            Log::error('permission.check_failed', [
                'user_id' => $user->id,
                'exception' => $e::class,
            ]);

            return false;
        }
    }
}
