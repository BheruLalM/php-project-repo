<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'badge_number',
        'rank',
        'station_branch',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ─────────────────── Relationships ───────────────────

    /**
     * Crime records assigned to this officer.
     */
    public function assignedCases(): HasMany
    {
        return $this->hasMany(CrimeRecord::class, 'assigned_officer_id');
    }

    /**
     * Complaints assigned to this officer.
     */
    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_officer_id');
    }

    /**
     * Audit log entries for this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // ─────────────────── Scopes ───────────────────

    /**
     * Scope: only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: only officers (not admins).
     */
    public function scopeOfficers($query)
    {
        return $query->where('role', 'officer');
    }
}
