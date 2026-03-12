<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Complaint $complaint): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Admin can update any complaint.
     * Officer can only update complaints assigned to them.
     */
    public function update(User $user, Complaint $complaint): bool
    {
        return $user->role === 'admin'
            || $user->id === $complaint->assigned_officer_id;
    }

    /**
     * Only admin can delete a complaint.
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->role === 'admin';
    }
}
