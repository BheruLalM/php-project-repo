<?php

namespace App\Policies;

use App\Models\Criminal;
use App\Models\User;

class CriminalPolicy
{
    /**
     * Any authenticated user can list criminals.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Any authenticated user can view a criminal profile.
     */
    public function view(User $user, Criminal $criminal): bool
    {
        return true;
    }

    /**
     * Both admin and officer can create a criminal record.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Both admin and officer can update a criminal record.
     */
    public function update(User $user, Criminal $criminal): bool
    {
        return true;
    }

    /**
     * Only admin can delete a criminal record.
     */
    public function delete(User $user, Criminal $criminal): bool
    {
        return $user->role === 'admin';
    }
}
