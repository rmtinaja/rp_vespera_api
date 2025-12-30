<?php

namespace App\Domain\Issues\Policies;

use App\Models\User;
use App\Domain\Issues\Models\Issues;

class IssuesPolicy
{
    public function update(User $user, Issues $issue): bool
    {
        return $user->id === $issue->created_by;
    }

    public function delete(User $user, Issues $issue): bool
    {
        return $user->id === $issue->created_by;
    }
}
