<?php

namespace App\Policies;

use App\Models\CrimeRecord;
use App\Models\User;

class CrimeRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CrimeRecord $crimeRecord): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Admin can update any record.
     * Officer can only update cases assigned to them.
     */
    public function update(User $user, CrimeRecord $crimeRecord): bool
    {
        return $user->role === 'admin'
            || $user->id === $crimeRecord->assigned_officer_id;
    }

    /**
     * Only admin can delete (soft-delete) a crime record.
     */
    public function delete(User $user, CrimeRecord $crimeRecord): bool
    {
        return $user->role === 'admin';
    }
}
